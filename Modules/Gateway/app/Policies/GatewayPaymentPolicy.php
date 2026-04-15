<?php

namespace Modules\Gateway\App\Policies;

use App\Models\User;
use Modules\Gateway\App\Models\GatewayPayment;

class GatewayPaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('gateway.dashboard.view');
    }

    public function view(User $user, GatewayPayment $gatewayPayment): bool
    {
        return $user->can('gateway.payments.view') || $user->can('gateway.dashboard.view');
    }
}
