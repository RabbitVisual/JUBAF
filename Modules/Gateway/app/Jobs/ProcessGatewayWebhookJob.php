<?php

namespace Modules\Gateway\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Modules\Gateway\App\Models\GatewayPayment;
use Modules\Gateway\App\Models\GatewayProviderAccount;
use Modules\Gateway\App\Models\GatewayWebhookEvent;
use Modules\Gateway\App\Services\PaymentOrchestrator;
use Modules\Gateway\App\Services\ProviderRegistry;

class ProcessGatewayWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $webhookEventId) {}

    public function handle(PaymentOrchestrator $orchestrator, ProviderRegistry $registry): void
    {
        $event = GatewayWebhookEvent::query()->find($this->webhookEventId);
        if (! $event || $event->processing_status !== GatewayWebhookEvent::STATUS_PENDING) {
            return;
        }

        $account = $event->gateway_provider_account_id
            ? GatewayProviderAccount::query()->find($event->gateway_provider_account_id)
            : null;
        if (! $account) {
            $event->update([
                'processing_status' => GatewayWebhookEvent::STATUS_FAILED,
                'error_message' => 'Conta de gateway não encontrada.',
            ]);

            return;
        }

        $laravelRequest = Request::create(
            '/gateway/webhooks',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $event->payload
        );

        $driver = $registry->get($event->driver);
        $parsed = $driver->parseWebhook($laravelRequest, $account);

        if ($parsed === null || ($parsed->newStatus === null && $parsed->providerReference === null)) {
            $event->update(['processing_status' => GatewayWebhookEvent::STATUS_PROCESSED]);

            return;
        }

        $payment = $this->resolvePayment($event->driver, $parsed->providerReference, $event->payload);

        if (! $payment) {
            $event->update([
                'processing_status' => GatewayWebhookEvent::STATUS_FAILED,
                'error_message' => 'Pagamento não encontrado para o webhook.',
            ]);

            return;
        }

        $event->gateway_payment_id = $payment->id;
        $event->save();

        $raw = $parsed->raw ?? json_decode($event->payload, true);

        if ($parsed->newStatus === GatewayPayment::STATUS_PAID) {
            $orchestrator->markPaid($payment, is_array($raw) ? $raw : null);
        } elseif (in_array($parsed->newStatus, [GatewayPayment::STATUS_FAILED, GatewayPayment::STATUS_CANCELLED], true)) {
            $orchestrator->markFailed($payment, $parsed->failureReason, is_array($raw) ? $raw : null);
        }

        $event->update(['processing_status' => GatewayWebhookEvent::STATUS_PROCESSED]);
    }

    private function resolvePayment(string $driver, ?string $providerReference, string $payloadJson): ?GatewayPayment
    {
        $data = json_decode($payloadJson, true);
        $data = is_array($data) ? $data : [];

        $uuid = Arr::get($data, 'data.object.client_reference_id')
            ?? Arr::get($data, 'data.object.metadata.gateway_payment_uuid')
            ?? Arr::get($data, 'metadata.gateway_payment_uuid')
            ?? Arr::get($data, 'external_reference');

        if (is_string($uuid) && $uuid !== '') {
            $p = GatewayPayment::query()->where('uuid', $uuid)->first();
            if ($p) {
                return $p;
            }
        }

        if (is_string($providerReference) && $providerReference !== '') {
            $p = GatewayPayment::query()
                ->where('external_reference', $providerReference)
                ->first();
            if ($p) {
                return $p;
            }
        }

        if ($driver === GatewayProviderAccount::DRIVER_STRIPE) {
            $sid = Arr::get($data, 'data.object.id');
            if (is_string($sid)) {
                return GatewayPayment::query()->where('external_reference', $sid)->first();
            }
        }

        return null;
    }
}
