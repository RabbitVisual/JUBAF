<?php

namespace Modules\Admin\App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;

class ExecutiveIntelligenceService
{
    private const CACHE_RANKING_TTL = 300;

    private const CACHE_FUNDS_TTL = 180;

    /**
     * Eventos atuais/próximos: arrecadação (tesouraria + inscrições confirmadas) vs despesas previstas (lançamentos de despesa pendentes/aprovadas na campanha).
     *
     * @return array<int, array{event_id:int,title:string,start_date:?string,collected:float,planned_expenses:float,registrations:int}>
     */
    public function eventFinancialKpis(): array
    {
        if (! Module::has('Events') || ! Module::isEnabled('Events') || ! Schema::hasTable('events')) {
            return [];
        }

        $now = Carbon::now();
        $rows = DB::table('events')
            ->where('status', 'published')
            ->where(function ($q) use ($now) {
                $q->where('start_date', '>=', $now->copy()->startOfDay())
                    ->orWhere(function ($q2) use ($now) {
                        $q2->whereNotNull('end_date')->where('end_date', '>=', $now);
                    });
            })
            ->orderBy('start_date')
            ->limit(12)
            ->get(['id', 'title', 'start_date', 'treasury_campaign_id']);

        $out = [];
        foreach ($rows as $ev) {
            $campaignId = $ev->treasury_campaign_id ? (int) $ev->treasury_campaign_id : null;

            $fromTreasury = 0.0;
            $plannedExpenses = 0.0;
            if ($campaignId && Schema::hasTable('financial_entries')) {
                $fromTreasury = (float) DB::table('financial_entries')
                    ->where('campaign_id', $campaignId)
                    ->where('type', 'income')
                    ->whereNull('deleted_at')
                    ->sum('amount');

                $plannedExpenses = (float) DB::table('financial_entries')
                    ->where('campaign_id', $campaignId)
                    ->where('type', 'expense')
                    ->whereNull('deleted_at')
                    ->where(function ($q) {
                        $q->whereIn('expense_status', ['pending', 'approved'])
                            ->orWhereNull('expense_status');
                    })
                    ->sum('amount');
            }

            $fromRegistrations = 0.0;
            $regCount = 0;
            if (Schema::hasTable('event_registrations')) {
                $fromRegistrations = (float) DB::table('event_registrations')
                    ->where('event_id', $ev->id)
                    ->where('status', 'confirmed')
                    ->whereNull('deleted_at')
                    ->sum('total_amount');
                $regCount = (int) DB::table('event_registrations')
                    ->where('event_id', $ev->id)
                    ->whereNull('deleted_at')
                    ->count();
            }

            $collected = max($fromTreasury, $fromRegistrations);
            if ($fromTreasury > 0 && $fromRegistrations > 0) {
                $collected = max($fromTreasury, $fromRegistrations);
            }

            $out[] = [
                'event_id' => (int) $ev->id,
                'title' => (string) $ev->title,
                'start_date' => $ev->start_date,
                'collected' => round($collected, 2),
                'planned_expenses' => round($plannedExpenses, 2),
                'registrations' => $regCount,
            ];
        }

        return $out;
    }

    /**
     * Ranking de inscrições por igreja (eventos publicados com data futura). Resultado em cache.
     *
     * @return Collection<int, object{name:string,city:?string,state:?string,registrations_count:int}>
     */
    public function churchEngagementRanking(): Collection
    {
        if (! Module::has('Events') || ! Module::isEnabled('Events')) {
            return collect();
        }
        if (! Schema::hasTable('event_registrations') || ! Schema::hasTable('churches') || ! Schema::hasTable('users')) {
            return collect();
        }

        return Cache::remember('admin.executive.church_engagement_rank_v1', self::CACHE_RANKING_TTL, function () {
            $now = Carbon::now();

            return DB::table('event_registrations as er')
                ->join('events as e', 'e.id', '=', 'er.event_id')
                ->join('users as u', 'u.id', '=', 'er.user_id')
                ->leftJoin('churches as c', 'c.id', '=', 'u.church_id')
                ->whereNull('er.deleted_at')
                ->where('e.status', 'published')
                ->where(function ($q) use ($now) {
                    $q->where('e.start_date', '>=', $now->copy()->startOfDay())
                        ->orWhere(function ($q2) use ($now) {
                            $q2->whereNotNull('e.end_date')->where('e.end_date', '>=', $now);
                        });
                })
                ->selectRaw('COALESCE(c.id, 0) as church_key')
                ->selectRaw('COALESCE(NULLIF(TRIM(c.name), ""), "Sem igreja vinculada") as name')
                ->addSelect('c.city', 'c.state')
                ->selectRaw('COUNT(er.id) as registrations_count')
                ->groupBy('church_key', 'name', 'c.city', 'c.state')
                ->orderByDesc('registrations_count')
                ->limit(15)
                ->get();
        });
    }

    /**
     * Saldo líquido por fundo: Fundo de Reserva e Fundo de Eventos (slugs JUBAF), quando existirem em financial_funds.
     *
     * @return array{reserve: array{label: string, net: float, slug: ?string}, events_fund: array{label: string, net: float, slug: ?string}}
     */
    public function treasuryFundSplit(): array
    {
        $empty = [
            'reserve' => ['label' => 'Fundo de Reserva', 'net' => 0.0, 'slug' => 'fundo-reserva'],
            'events_fund' => ['label' => 'Fundo de Eventos', 'net' => 0.0, 'slug' => 'fundo-eventos'],
        ];

        if (! Module::has('Treasury') || ! Module::isEnabled('Treasury') || ! Schema::hasTable('financial_funds') || ! Schema::hasTable('financial_entries')) {
            return $empty;
        }

        return Cache::remember('admin.executive.treasury_fund_split_v1', self::CACHE_FUNDS_TTL, function () use ($empty) {
            $reserveFund = DB::table('financial_funds')->where('slug', 'fundo-reserva')->first();
            $eventsFund = DB::table('financial_funds')->where('slug', 'fundo-eventos')->first();

            $net = function (?int $fundId): float {
                if (! $fundId) {
                    return 0.0;
                }
                $inc = (float) DB::table('financial_entries')
                    ->where('fund_id', $fundId)
                    ->where('type', 'income')
                    ->whereNull('deleted_at')
                    ->sum('amount');
                $exp = (float) DB::table('financial_entries')
                    ->where('fund_id', $fundId)
                    ->where('type', 'expense')
                    ->whereNull('deleted_at')
                    ->sum('amount');

                return round($inc - $exp, 2);
            };

            return [
                'reserve' => [
                    'label' => $reserveFund->name ?? $empty['reserve']['label'],
                    'net' => $net($reserveFund->id ?? null),
                    'slug' => $reserveFund->slug ?? 'fundo-reserva',
                ],
                'events_fund' => [
                    'label' => $eventsFund->name ?? $empty['events_fund']['label'],
                    'net' => $net($eventsFund->id ?? null),
                    'slug' => $eventsFund->slug ?? 'fundo-eventos',
                ],
            ];
        });
    }
}
