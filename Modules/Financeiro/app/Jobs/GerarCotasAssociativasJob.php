<?php

namespace Modules\Financeiro\App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Financeiro\App\Models\FinQuotaInvoice;
use Modules\Igrejas\App\Models\Church;

class GerarCotasAssociativasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ?string $billingMonth = null) {}

    public function handle(): void
    {
        $month = $this->billingMonth ?? Carbon::now()->format('Y-m');
        $amount = (float) config('financeiro.quota.monthly_invoice_amount', 0);
        if ($amount <= 0) {
            return;
        }

        $due = Carbon::createFromFormat('Y-m', $month)->endOfMonth()->toDateString();

        Church::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->chunkById(100, function ($churches) use ($month, $amount, $due): void {
                foreach ($churches as $church) {
                    FinQuotaInvoice::query()->firstOrCreate(
                        [
                            'church_id' => $church->id,
                            'billing_month' => $month,
                        ],
                        [
                            'amount' => $amount,
                            'currency' => 'BRL',
                            'status' => FinQuotaInvoice::STATUS_PENDING,
                            'due_on' => $due,
                            'metadata' => ['generated_by' => 'GerarCotasAssociativasJob'],
                        ]
                    );
                }
            });
    }
}
