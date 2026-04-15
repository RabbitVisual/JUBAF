<?php

namespace App\Policies;

use App\Models\Devotional;
use App\Models\User;

class DevotionalPolicy
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
        return $user->can('devotionals.view');
    }

    public function view(User $user, Devotional $devotional): bool
    {
        return $user->can('devotionals.view');
    }

    public function create(User $user): bool
    {
        return $user->can('devotionals.create');
    }

    public function update(User $user, Devotional $devotional): bool
    {
        return $user->can('devotionals.edit');
    }

    public function delete(User $user, Devotional $devotional): bool
    {
        return $user->can('devotionals.delete');
    }

    public function publish(User $user): bool
    {
        return $user->can('devotionals.publish');
    }
}
