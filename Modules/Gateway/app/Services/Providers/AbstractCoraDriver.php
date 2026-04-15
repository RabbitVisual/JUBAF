<?php

namespace Modules\Gateway\App\Services\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Modules\Gateway\App\Models\GatewayPayment;
use Modules\Gateway\App\Models\GatewayProviderAccount;
use Modules\Gateway\App\Services\Contracts\PaymentProviderContract;
use Modules\Gateway\App\Services\Dto\CreatePaymentResult;
use Modules\Gateway\App\Services\Dto\PaymentWebhookResult;

abstract class AbstractCoraDriver implements PaymentProviderContract
{
    abstract protected function defaultBaseUrl(): string;

    abstract protected function httpOptions(GatewayProviderAccount $account): array;

    protected function baseUrl(GatewayProviderAccount $account): string
    {
        return rtrim($account->base_url ?: $this->defaultBaseUrl(), '/');
    }

    protected function getAccessToken(GatewayProviderAccount $account): string
    {
        $c = $account->credentials;
        $cacheKey = 'gateway:cora_token:'.$account->id;

        return Cache::remember($cacheKey, now()->addMinutes(50), function () use ($account, $c) {
            $url = $this->baseUrl($account).'/oauth/token';
            $response = Http::withOptions($this->httpOptions($account))
                ->asForm()
                ->withBasicAuth($c['client_id'] ?? '', $c['client_secret'] ?? '')
                ->post($url, [
                    'grant_type' => 'client_credentials',
                ]);

            if (! $response->successful()) {
                throw new \RuntimeException('Cora OAuth falhou: '.$response->body());
            }

            $token = $response->json('access_token');
            if (! is_string($token) || $token === '') {
                throw new \RuntimeException('Resposta OAuth Cora sem access_token.');
            }

            return $token;
        });
    }

    public function createPayment(GatewayPayment $payment, GatewayProviderAccount $account): CreatePaymentResult
    {
        $token = $this->getAccessToken($account);
        $c = $account->credentials;

        $amountCents = (int) round(((float) $payment->amount) * 100);
        $due = now()->addDays((int) ($c['due_days'] ?? 3))->format('Y-m-d');

        $body = array_replace_recursive([
            'code' => $payment->uuid,
            'payment_terms' => [
                'due_date' => $due,
            ],
            'services' => [
                [
                    'name' => $payment->description ?: 'Pagamento JUBAF',
                    'amount' => [
                        'currency' => $payment->currency ?: 'BRL',
                        'amount' => $amountCents,
                    ],
                ],
            ],
        ], $c['invoice_body_merge'] ?? []);

        $url = $this->baseUrl($account).'/invoices';
        $response = Http::withOptions($this->httpOptions($account))
            ->withToken($token)
            ->withHeaders([
                'Idempotency-Key' => $payment->idempotency_key,
                'Accept' => 'application/json',
            ])
            ->post($url, $body);

        if (! $response->successful()) {
            throw new \RuntimeException('Cora criar fatura falhou: '.$response->body());
        }

        $json = $response->json();
        $id = Arr::get($json, 'id') ?? Arr::get($json, 'invoice_id') ?? Arr::get($json, 'data.id');
        $checkout = Arr::get($json, 'payment_url')
            ?? Arr::get($json, 'links.checkout')
            ?? Arr::get($json, 'bank_slip.barcode')
            ?? Arr::get($json, 'pix.copy_and_paste');

        return new CreatePaymentResult(
            providerReference: is_scalar($id) ? (string) $id : null,
            checkoutUrl: is_string($checkout) ? $checkout : null,
            clientSecret: null,
            rawResponse: is_array($json) ? $json : ['raw' => $response->body()],
        );
    }

    public function verifyWebhookSignature(\Illuminate\Http\Request $request, GatewayProviderAccount $account): array
    {
        $secret = $account->credentials['webhook_secret'] ?? null;
        if (! $secret) {
            return ['valid' => true, 'reason' => 'webhook_secret não configurado — aceite apenas em desenvolvimento'];
        }

        $sig = $request->header('X-Cora-Signature') ?? $request->header('X-Signature');
        if (! is_string($sig) || $sig === '') {
            return ['valid' => false, 'reason' => 'Assinatura em falta'];
        }

        $payload = $request->getContent();
        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $sig)
            ? ['valid' => true]
            : ['valid' => false, 'reason' => 'Assinatura inválida'];
    }

    public function parseWebhook(\Illuminate\Http\Request $request, GatewayProviderAccount $account): ?PaymentWebhookResult
    {
        $data = $request->all();
        $ref = Arr::get($data, 'invoice_id') ?? Arr::get($data, 'data.invoice_id') ?? Arr::get($data, 'id');
        $status = Arr::get($data, 'status') ?? Arr::get($data, 'data.status');
        $map = [
            'PAID' => \Modules\Gateway\App\Models\GatewayPayment::STATUS_PAID,
            'paid' => \Modules\Gateway\App\Models\GatewayPayment::STATUS_PAID,
            'CANCELLED' => \Modules\Gateway\App\Models\GatewayPayment::STATUS_CANCELLED,
            'cancelled' => \Modules\Gateway\App\Models\GatewayPayment::STATUS_CANCELLED,
            'FAILED' => \Modules\Gateway\App\Models\GatewayPayment::STATUS_FAILED,
        ];
        $newStatus = is_string($status) ? ($map[strtoupper($status)] ?? $map[$status] ?? null) : null;

        return new PaymentWebhookResult(
            providerReference: is_scalar($ref) ? (string) $ref : null,
            newStatus: $newStatus,
            raw: is_array($data) ? $data : null,
        );
    }
}
