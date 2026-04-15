<?php

namespace Modules\Financeiro\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Financeiro\App\Models\FinObligation;
use Modules\Financeiro\App\Models\FinTransaction;

class FinancialObligationPaid
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public FinObligation $obligation,
        public FinTransaction $transaction,
    ) {}
}
