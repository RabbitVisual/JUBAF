<?php

namespace Modules\Treasury\App\Listeners;

use Modules\PaymentGateway\App\Events\PaymentReceived;
use Modules\Treasury\App\Models\FinancialEntry;

class HandlePaymentReceived
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentReceived $event): void
    {
        $payment = $event->payment;

        // Idempotência forte por payment_id.
        if (FinancialEntry::query()->where('payment_id', $payment->id)->exists()) {
            return;
        }

        // Receita de inscrição de evento é responsabilidade única do RegistrationConfirmedListener.
        if ($payment->payment_type === 'event_registration') {
            return;
        }

        $campaignId = null;
        $category = 'donation';

        // Resolve Campaign from payable relationship
        if ($payment->payable_type === 'Modules\Treasury\App\Models\Campaign') {
            $campaignId = $payment->payable_id;
            $category = 'campaign';
        }

        // Specific category mapping based on payment_type
        if ($payment->payment_type === 'tithe') {
            $category = 'tithe';
        } elseif ($payment->payment_type === 'offering') {
            $category = 'offering';
        }

        FinancialEntry::create([
            'title' => ($payment->payment_type === 'tithe' ? 'Dizimo' : 'Doacao') . ' - ' . ($payment->payer_name ?? 'Anonimo'),
            'description' => $payment->description ?? 'Recebimento via Gateway',
            'amount' => $payment->amount,
            'type' => 'income',
            'category' => $category,
            'entry_date' => $payment->paid_at ?? now(),
            'user_id' => $payment->user_id,
            'payment_id' => $payment->id,

            'campaign_id' => $campaignId,
            'payment_method' => $payment->payment_method ?? 'gateway',
            'reference_number' => $payment->transaction_id,
            'metadata' => [
                'gateway_transaction_id' => $payment->gateway_transaction_id,
                'gateway_name' => optional($payment->gateway)->name,
            ],
        ]);
    }
}
