<?php

namespace Modules\Secretaria\App\Policies;

use App\Models\User;
use App\Support\JubafRoleRegistry;
use Modules\Secretaria\App\Models\SecretariaDocument;

use function user_can_access_diretoria_panel;

class SecretariaDocumentPolicy
{
    protected function directorate(User $user): bool
    {
        return $user->hasAnyRole(JubafRoleRegistry::directorateRoleNames());
    }

    protected function documentChurchAllows(User $user, SecretariaDocument $document): bool
    {
        if ($document->church_id === null) {
            return true;
        }

        if ($user->hasRole('super-admin') || $this->directorate($user)) {
            return true;
        }

        if (! $user->hasAnyRole(['lider', 'jovens', 'pastor'])) {
            return true;
        }

        return in_array((int) $document->church_id, $user->churchIdsForSecretariaScope(), true);
    }

    public function viewAny(User $user): bool
    {
        return $user->can('secretaria.documents.view')
            || user_can_access_diretoria_panel($user);
    }

    public function view(User $user, SecretariaDocument $document): bool
    {
        if (! $user->can('secretaria.documents.view')) {
            return false;
        }

        if ($document->visibility === 'directorate') {
            return $this->directorate($user);
        }

        if ($document->visibility === 'leaders') {
            if (! ($this->directorate($user) || $user->hasRole('lider'))) {
                return false;
            }

            return $this->documentChurchAllows($user, $document);
        }

        return $this->documentChurchAllows($user, $document);
    }

    public function create(User $user): bool
    {
        return $user->can('secretaria.documents.create');
    }

    public function update(User $user, SecretariaDocument $document): bool
    {
        return $user->can('secretaria.documents.edit');
    }

    public function delete(User $user, SecretariaDocument $document): bool
    {
        return $user->can('secretaria.documents.delete');
    }

    public function download(User $user, SecretariaDocument $document): bool
    {
        return $this->view($user, $document);
    }
}
