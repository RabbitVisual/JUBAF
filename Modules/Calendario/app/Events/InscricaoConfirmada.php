<?php

namespace Modules\Calendario\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Calendario\App\Models\CalendarRegistration;
use Modules\Gateway\App\Models\GatewayPayment;

class InscricaoConfirmada
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly CalendarRegistration $inscricao,
        public readonly GatewayPayment $payment,
    ) {}
}
