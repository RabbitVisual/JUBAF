<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->canAccessAny(['gerenciar_usuarios', 'ver_membros']);
    }

    public function view(User $user, User $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->canAccess('gerenciar_usuarios');
    }

    public function update(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->canAccess('gerenciar_usuarios');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->canAccess('gerenciar_usuarios');
    }
}
