<?php

namespace Modules\Financeiro\App\Policies;

use App\Models\User;
use Modules\Financeiro\App\Models\FinObligation;

class FinObligationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('financeiro.obligations.view');
    }

    public function view(User $user, FinObligation $obligation): bool
    {
        return $this->viewAny($user);
    }

    /**
     * Gerar cotas por igreja (ano associativo) — tesoureiro / presidente; não vice por defeito.
     */
    public function generate(User $user): bool
    {
        return $user->can('financeiro.obligations.manage');
    }
}
