<?php

namespace Modules\Igrejas\App\Repositories;

use App\Models\User;
use App\Support\ErpChurchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Igrejas\App\Models\Church;

class ChurchRepository
{
    /**
     * @param  Builder<Church>  $query
     */
    public function applyListFilters(Request $request, Builder $query): void
    {
        if ($request->filled('search')) {
            $s = $request->string('search');
            $query->where(function ($qq) use ($s) {
                $qq->where('name', 'like', '%'.$s.'%')
                    ->orWhere('trade_name', 'like', '%'.$s.'%')
                    ->orWhere('legal_name', 'like', '%'.$s.'%')
                    ->orWhere('city', 'like', '%'.$s.'%')
                    ->orWhere('email', 'like', '%'.$s.'%');
            });
        }

        if ($request->filled('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%'.$request->string('city').'%');
        }

        if ($request->filled('sector')) {
            $query->where('sector', 'like', '%'.$request->string('sector').'%');
        }

        if ($request->filled('jubaf_sector_id')) {
            $query->where('jubaf_sector_id', (int) $request->input('jubaf_sector_id'));
        }

        if ($request->filled('cooperation_status')) {
            $query->where('cooperation_status', $request->string('cooperation_status'));
        }

        if ($request->filled('crm_status')) {
            $query->where('crm_status', $request->string('crm_status'));
        }
    }

    /**
     * @return LengthAwarePaginator<int, Church>
     */
    public function paginateForUser(User $user, Request $request, int $perPage = 20): LengthAwarePaginator
    {
        $q = Church::query()->withCount(['users', 'jovensMembers', 'leaders']);
        ErpChurchScope::applyToChurchQuery($q, $user);
        $this->applyListFilters($request, $q);

        return $q->orderBy('name')->paginate($perPage)->withQueryString();
    }

    /**
     * @return array{total: int, active: int, inactive: int, inadimplente: int}
     */
    public function statsForUser(User $user): array
    {
        $base = Church::query();
        ErpChurchScope::applyToChurchQuery($base, $user);

        $total = (clone $base)->count();
        $active = (clone $base)->where('crm_status', Church::CRM_ATIVA)->count();
        $inactive = (clone $base)->where('crm_status', Church::CRM_INATIVA)->count();
        $inadimplente = (clone $base)->where('crm_status', Church::CRM_INADIMPLENTE)->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'inadimplente' => $inadimplente,
        ];
    }

    /**
     * Novos cadastros por `created_at` (janelas de 12 meses).
     *
     * @return array{last_12m: int, previous_12m: int}
     */
    public function growthStatsForUser(User $user): array
    {
        $base = Church::query();
        ErpChurchScope::applyToChurchQuery($base, $user);

        $t = now();
        $last12 = (clone $base)->where('created_at', '>=', $t->copy()->subMonths(12))->count();
        $prev12 = (clone $base)->whereBetween('created_at', [$t->copy()->subMonths(24), $t->copy()->subMonths(12)])->count();

        return [
            'last_12m' => $last12,
            'previous_12m' => $prev12,
        ];
    }

    public function exportQuery(User $user, Request $request): Builder
    {
        $q = Church::query();
        ErpChurchScope::applyToChurchQuery($q, $user);
        $this->applyListFilters($request, $q);

        return $q->orderBy('name');
    }
}
