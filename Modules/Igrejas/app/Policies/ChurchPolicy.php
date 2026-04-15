<?php

namespace Modules\Igrejas\App\Policies;

use App\Models\User;
use App\Support\JubafRoleRegistry;
use Modules\Igrejas\App\Models\Church;

class ChurchPolicy
{
    /**
     * Listagem global de congregações: super-admin, cargos de diretoria regional JUBAF (painel /diretoria)
     * e legado co-admin. Líder, jovens e pastor não entram aqui (sem papel de diretoria).
     */
    public static function canBrowseAllChurches(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Painel /diretoria: cargos de diretoria regional têm cadastro global de congregações (Estatuto / ASBAF).
        // Mantém-se igrejas.* para operações finas; instalações antigas podem não ter o pivot actualizado.
        if ($user->hasAnyRole(JubafRoleRegistry::directorateRoleNames())) {
            return true;
        }

        if ($user->hasAnyRole(array_filter([JubafRoleRegistry::legacyCoAdminName()]))) {
            return true;
        }

        return false;
    }

    /**
     * Líder, jovens ou pastor: só podem alterar dados da congregação vinculada ao perfil (church_id).
     * Não confundir com canBrowseAllChurches — aqui é o âmbito operacional quando têm permissão Spatie.
     */
    private static function userAffiliatedWithChurch(User $user, Church $church): bool
    {
        $ids = $user->affiliatedChurchIds();

        return in_array((int) $church->id, $ids, true);
    }

    private static function mayMutateOwnCongregationOnly(User $user, Church $church): bool
    {
        if (! static::userAffiliatedWithChurch($user, $church)) {
            return false;
        }

        return $user->hasAnyRole(['lider', 'jovens', 'pastor']);
    }

    public function viewAny(User $user): bool
    {
        return static::canBrowseAllChurches($user);
    }

    public function view(User $user, Church $church): bool
    {
        if (static::canBrowseAllChurches($user)) {
            if ($user->restrictsChurchDirectoryToSector()) {
                return $user->canAccessChurchInSectorScope($church);
            }

            return true;
        }

        if ($user->hasAnyRole(['lider', 'jovens'])) {
            return static::userAffiliatedWithChurch($user, $church);
        }

        if ($user->hasRole('pastor')) {
            return static::userAffiliatedWithChurch($user, $church);
        }

        return false;
    }

    public function create(User $user): bool
    {
        if (! $user->can('igrejas.create')) {
            return false;
        }

        return static::canBrowseAllChurches($user);
    }

    public function update(User $user, Church $church): bool
    {
        if (static::canBrowseAllChurches($user)) {
            if (! $user->can('igrejas.edit')) {
                return false;
            }

            if ($user->restrictsChurchDirectoryToSector()) {
                return $user->canAccessChurchInSectorScope($church);
            }

            return true;
        }

        return static::mayMutateOwnCongregationOnly($user, $church);
    }

    public function delete(User $user, Church $church): bool
    {
        if (! $user->can('igrejas.delete')) {
            return false;
        }

        if (static::canBrowseAllChurches($user)) {
            if ($user->restrictsChurchDirectoryToSector()) {
                return $user->canAccessChurchInSectorScope($church);
            }

            return true;
        }

        return static::mayMutateOwnCongregationOnly($user, $church);
    }

    public function activate(User $user, Church $church): bool
    {
        if (! $user->can('igrejas.activate')) {
            return false;
        }

        if (static::canBrowseAllChurches($user)) {
            if ($user->restrictsChurchDirectoryToSector()) {
                return $user->canAccessChurchInSectorScope($church);
            }

            return true;
        }

        return static::mayMutateOwnCongregationOnly($user, $church);
    }

    public function export(User $user): bool
    {
        return static::canBrowseAllChurches($user);
    }
}
