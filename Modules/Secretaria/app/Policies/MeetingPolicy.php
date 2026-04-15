<?php

namespace Modules\Secretaria\App\Policies;

use App\Models\User;
use Modules\Secretaria\App\Models\Meeting;

use function user_can_access_diretoria_panel;

class MeetingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('secretaria.meetings.view')
            || user_can_access_diretoria_panel($user);
    }

    public function view(User $user, Meeting $meeting): bool
    {
        return $user->can('secretaria.meetings.view')
            || user_can_access_diretoria_panel($user);
    }

    public function create(User $user): bool
    {
        return $user->can('secretaria.meetings.create');
    }

    public function update(User $user, Meeting $meeting): bool
    {
        return $user->can('secretaria.meetings.edit');
    }

    public function delete(User $user, Meeting $meeting): bool
    {
        return $user->can('secretaria.meetings.delete');
    }
}
