<?php

namespace Modules\Financeiro\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Financeiro\App\Models\FinObligation;

class FinancialObligationGenerated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public FinObligation $obligation) {}
}
