<?php

namespace Modules\Secretaria\App\Policies;

use App\Models\User;
use App\Support\JubafRoleRegistry;
use Modules\Secretaria\App\Models\Convocation;

use function user_can_access_diretoria_panel;

class ConvocationPolicy
{
    protected function directorate(User $user): bool
    {
        return $user->hasAnyRole(JubafRoleRegistry::directorateRoleNames());
    }

    public function viewAny(User $user): bool
    {
        return $user->can('secretaria.convocations.view')
            || user_can_access_diretoria_panel($user);
    }

    public function view(User $user, Convocation $convocation): bool
    {
        if (! $user->can('secretaria.convocations.view')) {
            return false;
        }

        if ($convocation->status === 'published') {
            return true;
        }

        return $this->directorate($user) || $user->can('secretaria.convocations.edit');
    }

    public function create(User $user): bool
    {
        return $user->can('secretaria.convocations.create');
    }

    public function update(User $user, Convocation $convocation): bool
    {
        if (! $user->can('secretaria.convocations.edit')) {
            return false;
        }

        if ($convocation->status === 'published') {
            return false;
        }

        return true;
    }

    public function delete(User $user, Convocation $convocation): bool
    {
        return $user->can('secretaria.convocations.delete') && $convocation->status !== 'published';
    }

    public function approve(User $user, Convocation $convocation): bool
    {
        return $user->can('secretaria.convocations.approve') && $convocation->status === 'pending_approval';
    }

    public function publish(User $user, Convocation $convocation): bool
    {
        return $user->can('secretaria.convocations.publish')
            && in_array($convocation->status, ['approved', 'pending_approval'], true);
    }
}
