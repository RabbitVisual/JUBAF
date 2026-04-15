<?php

namespace Modules\Financeiro\App\Policies;

use App\Models\User;
use Modules\Financeiro\App\Models\FinCategory;

class FinCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('financeiro.categories.view');
    }

    public function view(User $user, FinCategory $finCategory): bool
    {
        return $user->can('financeiro.categories.view');
    }

    public function create(User $user): bool
    {
        return $user->can('financeiro.categories.manage');
    }

    public function update(User $user, FinCategory $finCategory): bool
    {
        return $user->can('financeiro.categories.manage');
    }

    public function delete(User $user, FinCategory $finCategory): bool
    {
        if (! $user->can('financeiro.categories.manage')) {
            return false;
        }

        if ($finCategory->is_system) {
            return false;
        }

        return $finCategory->transactions()->doesntExist();
    }
}
