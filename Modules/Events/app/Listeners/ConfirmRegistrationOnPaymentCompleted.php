<?php

namespace Modules\Events\App\Listeners;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\PaymentGateway\App\Events\PaymentReceived;
use Modules\Events\App\Services\EventService;
use Modules\Events\App\Models\EventRegistration;

class ConfirmRegistrationOnPaymentCompleted
{
    protected EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function handle(PaymentReceived $event): void
    {
        $payment = $event->payment;

        if ($payment->payment_type !== 'event_registration') {
            return;
        }

        if ($payment->status !== 'completed') {
            return;
        }

        try {
            DB::transaction(function () use ($payment): void {
                $registration = EventRegistration::query()
                    ->whereKey($payment->payable_id)
                    ->lockForUpdate()
                    ->first();

                if (! $registration) {
                    return;
                }

                if ($registration->status !== EventRegistration::STATUS_PENDING) {
                    return;
                }

                $registration->update([
                    'payment_method' => $payment->payment_method ?? 'online',
                    'payment_reference' => $payment->transaction_id,
                    'paid_at' => $payment->paid_at ?? now(),
                ]);

                $this->eventService->confirmRegistration($registration);
                Log::info("Registration #{$registration->id} confirmed after payment #{$payment->id} completion");
            });
        } catch (\Exception $e) {
            Log::error("Error confirming registration for payment #{$payment->id}: ".$e->getMessage());
        }
    }
}
