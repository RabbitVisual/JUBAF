<?php

namespace Modules\PainelLider\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Financeiro\App\Models\FinQuotaInvoice;
use Modules\Financeiro\App\Services\FinanceiroService;
use Modules\Talentos\App\Models\TalentProfile;

class DashboardController extends Controller
{
    public function __construct(
        protected FinanceiroService $financeiroService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user()->load('church');
        $churchIds = $user->affiliatedChurchIds();
        $jovensCount = 0;
        if ($churchIds !== []) {
            $jovensCount = \App\Models\User::query()->role('jovens')->whereIn('church_id', $churchIds)->count();
        }

        $financialStatus = $this->financeiroService->obligationsStatusForChurchIds($churchIds);

        $recentInvoices = FinQuotaInvoice::query()
            ->with('church')
            ->when($churchIds !== [], fn (Builder $q) => $q->whereIn('church_id', $churchIds))
            ->orderByDesc('billing_month')
            ->limit(6)
            ->get();

        $talentPendingProfiles = TalentProfile::query()
            ->with(['user.church'])
            ->when($churchIds !== [], function (Builder $q) use ($churchIds): void {
                $q->whereHas('user', fn (Builder $u) => $u->whereIn('church_id', $churchIds));
            })
            ->whereExists(function ($sub): void {
                $sub->selectRaw('1')
                    ->from('talent_profile_skill')
                    ->whereColumn('talent_profile_skill.talent_profile_id', 'talent_profiles.id')
                    ->whereNull('talent_profile_skill.validated_at');
            })
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        $upcomingEvents = CalendarEvent::query()
            ->where('status', CalendarEvent::STATUS_PUBLISHED)
            ->whereIn('visibility', [CalendarEvent::VIS_PUBLIC, CalendarEvent::VIS_AUTH, CalendarEvent::VIS_LIDERES])
            ->where('start_date', '>=', now()->startOfDay())
            ->orderBy('start_date')
            ->limit(8)
            ->get();

        return view('painellider::dashboard', [
            'user' => $user,
            'jovensCount' => $jovensCount,
            'financialStatus' => $financialStatus,
            'recentInvoices' => $recentInvoices,
            'talentPendingProfiles' => $talentPendingProfiles,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }

    public function filtros()
    {
        return response()->json([]);
    }

    public function estatisticas()
    {
        return response()->json([]);
    }
}
