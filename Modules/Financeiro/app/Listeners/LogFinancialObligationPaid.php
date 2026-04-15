<?php

namespace Modules\Financeiro\App\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Financeiro\App\Events\FinancialObligationPaid;

class LogFinancialObligationPaid
{
    public function handle(FinancialObligationPaid $event): void
    {
        Log::info('erp.financial_obligation.paid', [
            'obligation_id' => $event->obligation->id,
            'fin_transaction_id' => $event->transaction->id,
            'church_id' => $event->obligation->church_id,
        ]);
    }
}
