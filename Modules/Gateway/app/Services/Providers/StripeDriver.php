<?php

namespace Modules\Gateway\App\Services\Providers;

use Illuminate\Http\Request;
use Modules\Gateway\App\Models\GatewayPayment;
use Modules\Gateway\App\Models\GatewayProviderAccount;
use Modules\Gateway\App\Services\Contracts\PaymentProviderContract;
use Modules\Gateway\App\Services\Dto\CreatePaymentResult;
use Modules\Gateway\App\Services\Dto\PaymentWebhookResult;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Checkout\Session;

class StripeDriver implements PaymentProviderContract
{
    public function driverKey(): string
    {
        return GatewayProviderAccount::DRIVER_STRIPE;
    }

    public function createPayment(GatewayPayment $payment, GatewayProviderAccount $account): CreatePaymentResult
    {
        $c = $account->credentials;
        Stripe::setApiKey($c['secret_key'] ?? '');

        $successUrl = route('gateway.public.return', ['uuid' => $payment->uuid]).'?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('gateway.public.return', ['uuid' => $payment->uuid]).'?cancelled=1';

        $session = Session::create([
            'mode' => 'payment',
            'client_reference_id' => $payment->uuid,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'gateway_payment_id' => (string) $payment->id,
                'gateway_payment_uuid' => $payment->uuid,
            ],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($payment->currency ?: 'brl'),
                    'unit_amount' => (int) round(((float) $payment->amount) * 100),
                    'product_data' => [
                        'name' => $payment->description ?: 'Pagamento JUBAF',
                    ],
                ],
                'quantity' => 1,
            ]],
        ]);

        return new CreatePaymentResult(
            providerReference: $session->id,
            checkoutUrl: $session->url,
            clientSecret: null,
            rawResponse: $session->toArray(),
        );
    }

    public function verifyWebhookSignature(Request $request, GatewayProviderAccount $account): array
    {
        $secret = $account->credentials['webhook_secret'] ?? '';
        if ($secret === '') {
            return ['valid' => true, 'reason' => 'webhook_secret não configurado'];
        }
        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature');
        if (! is_string($sig)) {
            return ['valid' => false, 'reason' => 'Stripe-Signature em falta'];
        }
        try {
            Webhook::constructEvent($payload, $sig, $secret);

            return ['valid' => true];
        } catch (\Throwable $e) {
            return ['valid' => false, 'reason' => $e->getMessage()];
        }
    }

    public function parseWebhook(Request $request, GatewayProviderAccount $account): ?PaymentWebhookResult
    {
        $payload = json_decode($request->getContent(), true);
        if (! is_array($payload)) {
            return null;
        }
        $type = $payload['type'] ?? '';
        $obj = $payload['data']['object'] ?? [];
        if (! is_array($obj)) {
            return null;
        }
        $ref = $obj['id'] ?? null;
        $status = null;
        if ($type === 'checkout.session.completed') {
            $status = $obj['payment_status'] === 'paid' ? GatewayPayment::STATUS_PAID : null;
        }
        if ($type === 'payment_intent.succeeded') {
            $status = GatewayPayment::STATUS_PAID;
        }
        if ($type === 'payment_intent.payment_failed') {
            $status = GatewayPayment::STATUS_FAILED;
        }

        return new PaymentWebhookResult(
            providerReference: is_string($ref) ? $ref : null,
            newStatus: $status,
            raw: $payload,
        );
    }
}
