<?php

namespace Modules\PaymentGateway\App\Console;

use Illuminate\Console\Command;
use Modules\PaymentGateway\App\Events\PaymentReceived;
use Modules\PaymentGateway\App\Models\Payment;

class ReconcileCompletedPaymentsCommand extends Command
{
    protected $signature = 'payment:reconcile-completed {--limit=200 : Maximum rows to process}';

    protected $description = 'Reprocess completed payments that are missing downstream effects.';

    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));

        $payments = Payment::query()
            ->where('status', 'completed')
            ->where(function ($q) {
                $q->whereNull('paid_at')->orWhereDoesntHave('financialEntry');
            })
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($payments->isEmpty()) {
            $this->info('No completed payments requiring reconciliation.');
            return self::SUCCESS;
        }

        foreach ($payments as $payment) {
            if (! $payment->paid_at) {
                $payment->forceFill(['paid_at' => now()])->save();
            }
            PaymentReceived::dispatch($payment);
        }

        $this->info("Reconciliation dispatched for {$payments->count()} payment(s).");

        return self::SUCCESS;
    }
}
