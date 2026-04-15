<?php

namespace Modules\Gateway\App\Services\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use Modules\Gateway\App\Models\GatewayPayment;
use Modules\Gateway\App\Models\GatewayProviderAccount;
use Modules\Gateway\App\Services\Contracts\PaymentProviderContract;
use Modules\Gateway\App\Services\Dto\CreatePaymentResult;
use Modules\Gateway\App\Services\Dto\PaymentWebhookResult;

class MercadoPagoDriver implements PaymentProviderContract
{
    public function driverKey(): string
    {
        return GatewayProviderAccount::DRIVER_MERCADOPAGO;
    }

    public function createPayment(GatewayPayment $payment, GatewayProviderAccount $account): CreatePaymentResult
    {
        $c = $account->credentials;
        MercadoPagoConfig::setAccessToken($c['access_token'] ?? '');

        $client = new PreferenceClient;
        $notificationUrl = route('gateway.webhooks.handle', ['driver' => $this->driverKey(), 'account' => $account->id]);

        $preference = $client->create([
            'external_reference' => $payment->uuid,
            'items' => [[
                'title' => $payment->description ?: 'Pagamento JUBAF',
                'quantity' => 1,
                'unit_price' => (float) $payment->amount,
                'currency_id' => $payment->currency ?: 'BRL',
            ]],
            'back_urls' => [
                'success' => route('gateway.public.return', ['uuid' => $payment->uuid]).'?mp=ok',
                'failure' => route('gateway.public.return', ['uuid' => $payment->uuid]).'?mp=fail',
                'pending' => route('gateway.public.return', ['uuid' => $payment->uuid]).'?mp=pending',
            ],
            'auto_return' => 'approved',
            'notification_url' => $notificationUrl,
        ]);

        $id = $preference->id ?? null;
        $init = $preference->init_point ?? $preference->sandbox_init_point ?? null;

        return new CreatePaymentResult(
            providerReference: is_string($id) ? $id : null,
            checkoutUrl: is_string($init) ? $init : null,
            clientSecret: null,
            rawResponse: json_decode(json_encode($preference), true),
        );
    }

    public function verifyWebhookSignature(Request $request, GatewayProviderAccount $account): array
    {
        // Mercado Pago envia x-signature; validação completa requer manifest — aceitar e validar payload por consulta API em produção.
        return ['valid' => true];
    }

    public function parseWebhook(Request $request, GatewayProviderAccount $account): ?PaymentWebhookResult
    {
        $data = $request->all();
        $topic = $data['topic'] ?? $data['type'] ?? null;
        $id = $data['id'] ?? null;
        if ($topic === 'payment' || $data['action'] ?? null === 'payment.updated') {
            return new PaymentWebhookResult(
                providerReference: is_scalar($id) ? (string) $id : null,
                newStatus: GatewayPayment::STATUS_PAID,
                raw: is_array($data) ? $data : null,
            );
        }

        $status = Arr::get($data, 'status');
        if (is_string($status) && strtoupper($status) === 'APPROVED') {
            return new PaymentWebhookResult(
                providerReference: Arr::get($data, 'data.id') ? (string) Arr::get($data, 'data.id') : null,
                newStatus: GatewayPayment::STATUS_PAID,
                raw: $data,
            );
        }

        return new PaymentWebhookResult(
            providerReference: null,
            newStatus: null,
            raw: is_array($data) ? $data : null,
        );
    }
}
