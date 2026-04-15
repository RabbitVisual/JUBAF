<?php

namespace Modules\Financeiro\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Financeiro\App\Models\FinObligation;
use Modules\Financeiro\App\Models\FinQuotaInvoice;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Gateway\App\Models\GatewayPayment;

class PagamentoConfirmado
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public FinTransaction $transaction,
        public ?GatewayPayment $gatewayPayment = null,
        public ?FinObligation $obligation = null,
        public ?FinQuotaInvoice $quotaInvoice = null,
    ) {}
}
