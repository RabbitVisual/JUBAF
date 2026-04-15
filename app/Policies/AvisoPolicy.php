<?php

namespace App\Policies;

use App\Models\User;
use Modules\Avisos\App\Models\Aviso;

class AvisoPolicy
{
    /**
     * Listagem e gestão no painel admin ou diretoria.
     */
    public function viewAny(User $user): bool
    {
        return user_can_access_admin_panel($user) || user_can_access_diretoria_panel($user);
    }

    public function view(User $user, Aviso $aviso): bool
    {
        return $this->viewAny($user);
    }

    /**
     * Criar, editar e eliminar avisos — super-admin, presidência, vices e secretários.
     */
    public function create(User $user): bool
    {
        return user_can_publish_avisos($user);
    }

    public function update(User $user, Aviso $aviso): bool
    {
        return user_can_publish_avisos($user);
    }

    public function delete(User $user, Aviso $aviso): bool
    {
        return user_can_publish_avisos($user);
    }
}
