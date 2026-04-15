<?php

namespace App\Policies;

use App\Models\BoardMember;
use App\Models\User;

class BoardMemberPolicy
{
    public function before(?User $user, string $ability): ?bool
    {
        if ($user && $user->hasRole('super-admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('board_members.view');
    }

    public function view(User $user, BoardMember $boardMember): bool
    {
        return $user->can('board_members.view');
    }

    public function create(User $user): bool
    {
        return $user->can('board_members.create');
    }

    public function update(User $user, BoardMember $boardMember): bool
    {
        return $user->can('board_members.edit');
    }

    public function delete(User $user, BoardMember $boardMember): bool
    {
        return $user->can('board_members.delete');
    }
}
