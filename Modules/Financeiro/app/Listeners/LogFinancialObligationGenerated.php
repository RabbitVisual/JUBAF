<?php

namespace Modules\Financeiro\App\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Financeiro\App\Events\FinancialObligationGenerated;

class LogFinancialObligationGenerated
{
    public function handle(FinancialObligationGenerated $event): void
    {
        Log::info('erp.financial_obligation.generated', [
            'obligation_id' => $event->obligation->id,
            'church_id' => $event->obligation->church_id,
            'assoc_start_year' => $event->obligation->assoc_start_year,
            'amount' => $event->obligation->amount,
        ]);
    }
}
