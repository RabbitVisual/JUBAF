<?php

namespace Modules\Secretaria\App\Policies;

use App\Models\User;
use App\Support\JubafRoleRegistry;
use Modules\Igrejas\App\Models\Church;
use Modules\Secretaria\App\Models\Minute;

use function user_can_access_diretoria_panel;

class MinutePolicy
{
    protected function directorate(User $user): bool
    {
        return $user->hasAnyRole(JubafRoleRegistry::directorateRoleNames());
    }

    protected function bypassesPublishedChurchScope(User $user): bool
    {
        return $user->hasRole('super-admin')
            || $user->can('secretaria.minutes.edit')
            || ($this->directorate($user) && ! $user->restrictsChurchDirectoryToSector());
    }

    /**
     * Diretoria com âmbito de setor: vê atas regionais ou da sua região de igrejas.
     */
    protected function directorateCanAccessMinute(User $user, Minute $minute): bool
    {
        if (! $this->directorate($user)) {
            return false;
        }

        if (! $user->restrictsChurchDirectoryToSector()) {
            return true;
        }

        if ($minute->church_id === null) {
            return true;
        }

        $church = Church::query()->find((int) $minute->church_id);

        return $church && $user->canAccessChurchInSectorScope($church);
    }

    public function viewAny(User $user): bool
    {
        return $user->can('secretaria.minutes.view')
            || user_can_access_diretoria_panel($user);
    }

    public function view(User $user, Minute $minute): bool
    {
        if (! $user->can('secretaria.minutes.view')) {
            return false;
        }

        if (in_array($minute->status, ['published', 'archived'], true)) {
            if ($this->bypassesPublishedChurchScope($user)) {
                return true;
            }

            if ($this->directorate($user) && $user->restrictsChurchDirectoryToSector()) {
                return $this->directorateCanAccessMinute($user, $minute);
            }

            return $minute->isPublishedVisibleToChurchScopedUser($user);
        }

        if ($user->hasAnyRole(['lider', 'pastor', 'jovens'])) {
            return false;
        }

        if ($user->can('secretaria.minutes.edit') || $user->can('secretaria.minutes.sign')) {
            return true;
        }

        return $this->directorateCanAccessMinute($user, $minute);
    }

    public function create(User $user): bool
    {
        return $user->can('secretaria.minutes.create');
    }

    public function update(User $user, Minute $minute): bool
    {
        if (! $user->can('secretaria.minutes.edit')) {
            return false;
        }

        if ($minute->locked_at) {
            return false;
        }

        return $minute->status === 'draft';
    }

    public function delete(User $user, Minute $minute): bool
    {
        if (! $user->can('secretaria.minutes.delete')) {
            return false;
        }

        return $minute->locked_at === null && $minute->status !== 'published';
    }

    public function submit(User $user, Minute $minute): bool
    {
        return $user->can('secretaria.minutes.request_signatures')
            && in_array($minute->status, ['draft'], true)
            && $minute->locked_at === null;
    }

    public function requestSignatures(User $user, Minute $minute): bool
    {
        return $user->can('secretaria.minutes.request_signatures')
            && $minute->status === 'draft'
            && $minute->locked_at === null;
    }

    public function sign(User $user, Minute $minute): bool
    {
        return $user->can('secretaria.minutes.sign')
            && $minute->status === 'pending_signatures'
            && $minute->locked_at === null;
    }

    public function archive(User $user, Minute $minute): bool
    {
        return $user->can('secretaria.minutes.publish')
            && $minute->status === 'published'
            && $minute->locked_at !== null;
    }

    public function downloadPdf(User $user, Minute $minute): bool
    {
        return $this->view($user, $minute);
    }
}
