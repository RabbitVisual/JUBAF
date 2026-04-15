<?php

namespace App\Support;

use App\Models\User;
use InvalidArgumentException;

final class RoleAssignmentGuard
{
    /**
     * Funções que apenas super-admin pode atribuir ou remover.
     */
    public static function superAdminOnlyRoleNames(): array
    {
        return JubafRoleRegistry::superAdminRoleNames();
    }

    public static function actorIsSuperAdmin(?User $actor): bool
    {
        return $actor && $actor->hasRole('super-admin');
    }

    public static function actorIsDirectorateExecutive(?User $actor): bool
    {
        return $actor && $actor->hasAnyRole(JubafRoleRegistry::directorateExecutiveRoleNames());
    }

    /**
     * @param  list<string>  $roleNames
     */
    public static function assertActorMayAssignRoles(?User $actor, array $roleNames): void
    {
        $roleNames = array_values(array_filter($roleNames));

        foreach ($roleNames as $roleName) {
            if (in_array($roleName, self::superAdminOnlyRoleNames(), true) && ! self::actorIsSuperAdmin($actor)) {
                throw new InvalidArgumentException('A função Super Administrador só pode ser atribuída no painel de super-admin.');
            }
        }
    }

    public static function assertActorMayManageUser(?User $actor, User $target): void
    {
        if ($target->hasRole('super-admin') && ! self::actorIsSuperAdmin($actor)) {
            throw new InvalidArgumentException('Não pode gerir contas de Super Administrador.');
        }
    }

    /**
     * @param  list<string>|null  $newRoleNames  null = não alterar funções
     */
    public static function assertNotDemotingLastSuperAdmin(User $target, ?array $newRoleNames): void
    {
        if ($newRoleNames === null) {
            return;
        }

        if (! $target->hasRole('super-admin')) {
            return;
        }

        $stillHasSuperAdmin = in_array('super-admin', $newRoleNames, true);
        if ($stillHasSuperAdmin) {
            return;
        }

        $count = User::role('super-admin')->where('active', true)->count();
        if ($count <= 1) {
            throw new InvalidArgumentException('Não pode remover o último Super Administrador ativo do sistema.');
        }
    }

    public static function assertNotDeletingLastSuperAdmin(User $target): void
    {
        if (! $target->hasRole('super-admin')) {
            return;
        }

        $count = User::role('super-admin')->where('active', true)->count();
        if ($count <= 1) {
            throw new InvalidArgumentException('Não pode eliminar o último Super Administrador ativo.');
        }
    }

    public static function assertNotDeactivatingLastSuperAdmin(User $target): void
    {
        if (! $target->hasRole('super-admin') || ! $target->active) {
            return;
        }

        $count = User::role('super-admin')->where('active', true)->count();
        if ($count <= 1) {
            throw new InvalidArgumentException('Não pode desativar o último Super Administrador ativo.');
        }
    }

    public static function assertNotSelfDestruct(?User $actor, User $target): void
    {
        if ($actor && $actor->id === $target->id && $target->hasRole('super-admin')) {
            throw new InvalidArgumentException('Não pode eliminar ou desativar a sua própria conta de Super Administrador.');
        }
    }
}
