<?php

namespace Modules\Gateway\App\Policies;

use App\Models\User;
use Modules\Gateway\App\Models\GatewayProviderAccount;

class GatewayProviderAccountPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('gateway.accounts.manage');
    }

    public function view(User $user, GatewayProviderAccount $gatewayProviderAccount): bool
    {
        return $user->can('gateway.accounts.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('gateway.accounts.manage');
    }

    public function update(User $user, GatewayProviderAccount $gatewayProviderAccount): bool
    {
        return $user->can('gateway.accounts.manage');
    }

    public function delete(User $user, GatewayProviderAccount $gatewayProviderAccount): bool
    {
        return $user->can('gateway.accounts.manage');
    }
}
