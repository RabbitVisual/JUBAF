<?php

namespace Modules\Gateway\App\Services\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Modules\Gateway\App\Models\GatewayPayment;
use Modules\Gateway\App\Models\GatewayProviderAccount;
use Modules\Gateway\App\Services\Contracts\PaymentProviderContract;
use Modules\Gateway\App\Services\Dto\CreatePaymentResult;
use Modules\Gateway\App\Services\Dto\PaymentWebhookResult;

class PagarMeDriver implements PaymentProviderContract
{
    public function driverKey(): string
    {
        return GatewayProviderAccount::DRIVER_PAGARME;
    }

    public function createPayment(GatewayPayment $payment, GatewayProviderAccount $account): CreatePaymentResult
    {
        $c = $account->credentials;
        $base = rtrim($account->base_url ?: 'https://api.pagar.me/core/v5', '/');
        $secret = $c['secret_key'] ?? '';

        $amountCents = (int) round(((float) $payment->amount) * 100);
        $body = [
            'code' => $payment->uuid,
            'items' => [[
                'amount' => $amountCents,
                'description' => $payment->description ?: 'Pagamento JUBAF',
                'quantity' => 1,
                'code' => $payment->uuid,
            ]],
            'customer' => [
                'name' => 'JUBAF',
            ],
            'closed' => true,
        ];

        $response = Http::withBasicAuth($secret, '')
            ->acceptJson()
            ->post($base.'/orders', $body);

        if (! $response->successful()) {
            throw new \RuntimeException('Pagar.me criar pedido falhou: '.$response->body());
        }

        $json = $response->json();
        $orderId = Arr::get($json, 'id');
        $charges = Arr::get($json, 'charges', []);
        $last = is_array($charges) && $charges !== [] ? $charges[0] : [];
        $checkout = Arr::get($last, 'last_transaction.gateway_url');

        return new CreatePaymentResult(
            providerReference: is_scalar($orderId) ? (string) $orderId : null,
            checkoutUrl: is_string($checkout) ? $checkout : null,
            clientSecret: null,
            rawResponse: is_array($json) ? $json : null,
        );
    }

    public function verifyWebhookSignature(Request $request, GatewayProviderAccount $account): array
    {
        $secret = $account->credentials['webhook_secret'] ?? '';
        if ($secret === '') {
            return ['valid' => true];
        }
        $sig = $request->header('X-Hub-Signature');
        if (! is_string($sig)) {
            return ['valid' => false, 'reason' => 'Assinatura em falta'];
        }

        $payload = $request->getContent();
        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals('sha256='.$expected, $sig) || hash_equals($expected, $sig)
            ? ['valid' => true]
            : ['valid' => false, 'reason' => 'Assinatura inválida'];
    }

    public function parseWebhook(Request $request, GatewayProviderAccount $account): ?PaymentWebhookResult
    {
        $data = $request->all();
        $status = Arr::get($data, 'data.status') ?? Arr::get($data, 'data.charges.0.status');
        $id = Arr::get($data, 'data.id');
        $map = [
            'paid' => GatewayPayment::STATUS_PAID,
            'failed' => GatewayPayment::STATUS_FAILED,
            'canceled' => GatewayPayment::STATUS_CANCELLED,
        ];
        $new = is_string($status) ? ($map[strtolower($status)] ?? null) : null;

        return new PaymentWebhookResult(
            providerReference: is_scalar($id) ? (string) $id : null,
            newStatus: $new,
            raw: is_array($data) ? $data : null,
        );
    }
}
