<?php

namespace Modules\PaymentGateway\App\Observers;

use Illuminate\Support\Facades\Event;
use Modules\PaymentGateway\App\Models\Payment;

class PaymentObserver
{
    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        // Mantemos este evento string apenas por retrocompatibilidade (notifications/listeners legados).
        if ($payment->isDirty('status') && $payment->status === 'completed') {
            Event::dispatch('payment.completed', $payment);
        }
    }
}
