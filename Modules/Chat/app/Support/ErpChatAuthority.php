<?php

namespace Modules\Chat\App\Support;

use App\Models\User;
use App\Support\JubafRoleRegistry;

final class ErpChatAuthority
{
    /**
     * Diretoria pode contactar líderes/pastores/diretoria; líder/pastor só contacta agentes (diretoria/suporte).
     */
    public static function canInitiateDirect(User $auth, User $target): bool
    {
        if ((int) $auth->id === (int) $target->id) {
            return false;
        }

        $auth->loadMissing('roles');
        $target->loadMissing('roles');

        if ($auth->hasAnyRole(JubafRoleRegistry::superAdminRoleNames())) {
            return true;
        }

        $directorate = JubafRoleRegistry::directorateRoleNames();
        $agents = jubaf_chat_agent_role_names();

        if ($auth->hasAnyRole($directorate)) {
            return $target->hasAnyRole(array_merge(
                $directorate,
                ['lider', 'pastor'],
                JubafRoleRegistry::superAdminRoleNames(),
            ));
        }

        if ($auth->hasAnyRole(['lider', 'pastor'])) {
            return $target->hasAnyRole($agents);
        }

        return false;
    }
}
