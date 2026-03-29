<?php

namespace Modules\PaymentGateway\App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\PaymentGateway\App\Models\Payment;

class PaymentSettlementService
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Settle a payment as completed in an idempotent way.
     *
     * @param  string  $source  webhook, checkout_return, admin, system
     * @param  array<string, mixed>  $auditPayload
     */
    public function settleAsCompleted(Payment $payment, string $source = 'system', array $auditPayload = []): Payment
    {
        $locked = DB::transaction(function () use ($payment, $source, $auditPayload) {
            /** @var Payment|null $row */
            $row = Payment::query()->lockForUpdate()->find($payment->id);
            if (! $row) {
                throw new \RuntimeException("Pagamento {$payment->id} não encontrado.");
            }

            if ($row->status === 'completed') {
                return $row;
            }

            $this->paymentService->confirmPayment($row, $source, $auditPayload);

            return $row->fresh();
        });

        Log::info('Payment settled as completed', [
            'payment_id' => $locked->id,
            'transaction_id' => $locked->transaction_id,
            'source' => $source,
        ]);

        return $locked;
    }
}
