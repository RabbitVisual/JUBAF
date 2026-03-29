<?php

namespace App\Services;

use App\Models\MemberPanelModuleGrant;
use App\Models\User;
use Modules\Treasury\App\Models\TreasuryPermission;

class MemberPanelAccess
{
    public const MODULE_TREASURY = 'treasury';

    public const MODULE_CHURCHES = 'churches';

    public const MODULE_DELEGATION_UI = 'delegation_ui';

    public const MODULE_GOVERNANCE = 'governance';

    public const MODULE_COUNCIL = 'council';

    public const MODULE_FIELD = 'field';

    public const MODULE_LIDERANCA = 'lideranca';

    /** @var list<string> */
    public static function moduleKeys(): array
    {
        return [
            self::MODULE_TREASURY,
            self::MODULE_CHURCHES,
            self::MODULE_GOVERNANCE,
            self::MODULE_COUNCIL,
            self::MODULE_FIELD,
            self::MODULE_LIDERANCA,
            self::MODULE_DELEGATION_UI,
        ];
    }

    public static function label(string $key): string
    {
        return match ($key) {
            self::MODULE_TREASURY => 'Tesouraria (painel)',
            self::MODULE_CHURCHES => 'Diretório / gestão de igrejas (painel)',
            self::MODULE_GOVERNANCE => 'Governança — assembleias e comunicados (painel)',
            self::MODULE_COUNCIL => 'Conselho de coordenação (painel)',
            self::MODULE_FIELD => 'Campo / visitas (painel)',
            self::MODULE_LIDERANCA => 'Caravana / liderança local (painel)',
            self::MODULE_DELEGATION_UI => 'Delegar acessos ao painel (outros utilizadores)',
            default => $key,
        };
    }

    /**
     * Utilizadores que podem conceder grants (presidente, admin técnico, opcional secretário geral com permissão).
     */
    public static function canDelegateGrants(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->hasRole('presidente')) {
            return true;
        }
        if ($user->isSecretaryGeneral() && $user->canAccess('delegar_acesso_painel')) {
            return true;
        }

        return false;
    }

    public static function hasActiveGrant(User $user, string $moduleKey): bool
    {
        return MemberPanelModuleGrant::query()
            ->where('user_id', $user->id)
            ->where('module_key', $moduleKey)
            ->where(function ($q): void {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    public static function canUseModule(User $user, string $moduleKey): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return match ($moduleKey) {
            self::MODULE_TREASURY => self::treasuryVisible($user),
            self::MODULE_CHURCHES => self::churchesVisible($user),
            self::MODULE_GOVERNANCE => self::governanceVisible($user),
            self::MODULE_COUNCIL => self::councilVisible($user),
            self::MODULE_FIELD => self::fieldVisible($user),
            self::MODULE_LIDERANCA => self::liderancaVisible($user),
            self::MODULE_DELEGATION_UI => self::canDelegateGrants($user),
            default => false,
        };
    }

    protected static function treasuryVisible(User $user): bool
    {
        if ($user->canAccess('gerenciar_financeiro')) {
            return true;
        }
        if (TreasuryPermission::where('user_id', $user->id)->exists()) {
            return true;
        }
        if (self::hasActiveGrant($user, self::MODULE_TREASURY)) {
            return true;
        }

        return false;
    }

    protected static function churchesVisible(User $user): bool
    {
        if ($user->canAccess('gerenciar_igrejas')) {
            return true;
        }
        if ($user->isYouthLeader()) {
            return true;
        }
        if (self::hasActiveGrant($user, self::MODULE_CHURCHES)) {
            return true;
        }

        return false;
    }

    protected static function governanceVisible(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->canAccessAny(['governance_manage', 'governance_view'])) {
            return true;
        }

        return self::hasActiveGrant($user, self::MODULE_GOVERNANCE);
    }

    protected static function councilVisible(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->canAccessAny(['council_manage', 'council_view'])) {
            return true;
        }

        return self::hasActiveGrant($user, self::MODULE_COUNCIL);
    }

    protected static function fieldVisible(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->canAccessAny(['field_manage', 'field_view'])) {
            return true;
        }

        return self::hasActiveGrant($user, self::MODULE_FIELD);
    }

    protected static function liderancaVisible(User $user): bool
    {
        if ($user->isYouthLeader()) {
            return true;
        }

        return self::hasActiveGrant($user, self::MODULE_LIDERANCA);
    }
}
