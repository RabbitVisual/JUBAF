<?php

namespace App\Support;

use Spatie\Permission\Models\Role;

final class JubafRoleRegistry
{
    public static function label(string $roleName): string
    {
        return config('jubaf_roles.labels.'.$roleName, ucwords(str_replace('-', ' ', $roleName)));
    }

    public static function description(string $roleName): ?string
    {
        return config('jubaf_roles.descriptions.'.$roleName);
    }

    public static function tier(string $roleName): string
    {
        return config('jubaf_roles.tiers.'.$roleName, 'custom');
    }

    public static function sortKey(string $roleName): int
    {
        return (int) config('jubaf_roles.sort_order.'.$roleName, 500);
    }

    public static function isSystemRole(string $roleName): bool
    {
        return in_array($roleName, config('jubaf_roles.system_roles', []), true);
    }

    public static function superAdminRoleNames(): array
    {
        return config('jubaf_roles.super_admin', []);
    }

    public static function directorateRoleNames(): array
    {
        return config('jubaf_roles.directorate', []);
    }

    /**
     * @return array<int, string>
     */
    public static function directorateExecutiveRoleNames(): array
    {
        return config('jubaf_roles.directorate_executive', []);
    }

    public static function legacyCoAdminName(): string
    {
        return config('jubaf_roles.legacy.co_admin', 'co-admin');
    }

    /**
     * Ordena coleção de roles Spatie para exibição no painel.
     *
     * @param  \Illuminate\Support\Collection<int, Role>  $roles
     * @return \Illuminate\Support\Collection<int, Role>
     */
    public static function sortRolesForDisplay($roles)
    {
        return $roles->sortBy(function (Role $role) {
            return [self::sortKey($role->name), $role->name];
        })->values();
    }
}
