<?php

namespace Modules\Gateway\App\Services;

use Illuminate\Support\Facades\DB;
use Modules\Gateway\App\Models\GatewayPayment;
use Modules\Gateway\App\Models\GatewayProviderAccount;

class PaymentOrchestrator
{
    public function __construct(
        private readonly ProviderRegistry $registry,
        private readonly GatewayAuditLogger $audit,
    ) {}

    public function resolveDefaultAccount(): ?GatewayProviderAccount
    {
        $q = GatewayProviderAccount::query()->where('is_enabled', true);

        return (clone $q)->where('is_default', true)->first()
            ?? $q->orderBy('id')->first();
    }

    public function initiatePayment(GatewayPayment $payment, ?GatewayProviderAccount $account = null): GatewayPayment
    {
        $account ??= $payment->gateway_provider_account_id
            ? GatewayProviderAccount::query()->findOrFail($payment->gateway_provider_account_id)
            : $this->resolveDefaultAccount();

        if (! $account) {
            throw new \RuntimeException('Nenhuma conta de gateway Ativa configurada.');
        }

        $payment->gateway_provider_account_id = $account->id;
        $payment->driver = $account->driver;
        $payment->save();

        $driver = $this->registry->forAccount($account);
        $result = $driver->createPayment($payment, $account);

        $payment->refresh();
        $payment->provider_reference = $result->providerReference ?? $payment->provider_reference;
        $payment->checkout_url = $result->checkoutUrl ?? $payment->checkout_url;
        $payment->client_secret = $result->clientSecret ?? $payment->client_secret;
        $payment->raw_last_payload = $result->rawResponse ?? $payment->raw_last_payload;
        $payment->save();

        $this->audit->log('payment.initiated', $payment, $payment->payable, [
            'driver' => $account->driver,
            'provider_reference' => $payment->provider_reference,
        ]);

        return $payment;
    }

    public function markPaid(GatewayPayment $payment, ?array $raw = null): void
    {
        if ($payment->status === GatewayPayment::STATUS_PAID) {
            return;
        }

        DB::transaction(function () use ($payment, $raw): void {
            $payment->status = GatewayPayment::STATUS_PAID;
            $payment->paid_at = now();
            if ($raw !== null) {
                $payment->raw_last_payload = $raw;
            }
            $payment->save();
        });

        $this->audit->log('payment.paid', $payment, $payment->payable);

        \Modules\Gateway\App\Jobs\ReconcilePaymentToFinTransactionJob::dispatch($payment->id);
    }

    public function markFailed(GatewayPayment $payment, ?string $reason = null, ?array $raw = null): void
    {
        $payment->status = GatewayPayment::STATUS_FAILED;
        $payment->failure_reason = $reason;
        if ($raw !== null) {
            $payment->raw_last_payload = $raw;
        }
        $payment->save();

        $this->audit->log('payment.failed', $payment, $payment->payable, ['reason' => $reason]);
    }
}
