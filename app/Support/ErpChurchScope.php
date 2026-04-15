<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Filtros ERP por setor (vice-presidentes com jubaf_sector_id).
 */
final class ErpChurchScope
{
    public static function userRestrictsToSector(User $user): bool
    {
        return $user->restrictsChurchDirectoryToSector();
    }

    /**
     * Limita consultas de congregações ao setor do utilizador.
     */
    public static function applyToChurchQuery(Builder $query, User $user): void
    {
        if (! self::userRestrictsToSector($user)) {
            return;
        }

        $sid = $user->jubaf_sector_id;
        $query->where('jubaf_sector_id', $sid);
    }

    /**
     * Transacções: regionais (sem igreja) + igrejas do setor.
     */
    public static function applyToFinTransactionQuery(Builder $query, User $user): void
    {
        if (! self::userRestrictsToSector($user)) {
            return;
        }

        $sid = $user->jubaf_sector_id;
        $query->where(function (Builder $w) use ($sid) {
            $w->whereNull('church_id')
                ->orWhereIn('church_id', function ($sub) use ($sid) {
                    $sub->select('id')
                        ->from('igrejas_churches')
                        ->where('jubaf_sector_id', $sid);
                });
        });
    }

    /**
     * Atas: federação (sem igreja) ou igreja do setor.
     */
    public static function applyToSecretariaMinuteQuery(Builder $query, User $user): void
    {
        if (! self::userRestrictsToSector($user)) {
            return;
        }

        $sid = $user->jubaf_sector_id;
        $query->where(function (Builder $w) use ($sid) {
            $w->whereNull('church_id')
                ->orWhereIn('church_id', function ($sub) use ($sid) {
                    $sub->select('id')
                        ->from('igrejas_churches')
                        ->where('jubaf_sector_id', $sid);
                });
        });
    }

    /**
     * Obrigações financeiras (cotas): apenas igrejas do setor do VP.
     *
     * @param  Builder<\Modules\Financeiro\App\Models\FinObligation>  $query
     */
    public static function applyToFinObligationQuery(Builder $query, User $user): void
    {
        if (! self::userRestrictsToSector($user)) {
            return;
        }

        $sid = $user->jubaf_sector_id;
        $query->whereIn('church_id', function ($sub) use ($sid) {
            $sub->select('id')
                ->from('igrejas_churches')
                ->where('jubaf_sector_id', $sid);
        });
    }

    /**
     * Faturas mensais de cotas: apenas igrejas do setor do VP.
     *
     * @param  Builder<\Modules\Financeiro\App\Models\FinQuotaInvoice>  $query
     */
    public static function applyToFinQuotaInvoiceQuery(Builder $query, User $user): void
    {
        if (! self::userRestrictsToSector($user)) {
            return;
        }

        $sid = $user->jubaf_sector_id;
        $query->whereIn('church_id', function ($sub) use ($sid) {
            $sub->select('id')
                ->from('igrejas_churches')
                ->where('jubaf_sector_id', $sid);
        });
    }

    /**
     * Lista de IDs de igrejas no setor do utilizador (vazio se não aplicável).
     *
     * @return list<int>|null
     */
    public static function churchIdsForUserSector(User $user): ?array
    {
        if (! self::userRestrictsToSector($user)) {
            return null;
        }

        return DB::table('igrejas_churches')
            ->where('jubaf_sector_id', $user->jubaf_sector_id)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
    }
}
