<?php

namespace Modules\Igrejas\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ErpChurchScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\ChurchChangeRequest;
use Modules\Igrejas\App\Repositories\ChurchRepository;

class DiretoriaIgrejasDashboardController extends Controller
{
    public function index(Request $request, ChurchRepository $churches): View
    {
        $this->authorize('viewAny', Church::class);

        $user = $request->user();
        abort_unless($user, 403);

        $crmStats = $churches->statsForUser($user);
        $growth = $churches->growthStatsForUser($user);

        $totalChurches = $crmStats['total'];
        $activeChurches = $crmStats['active'];
        $inactiveChurches = $crmStats['inactive'];
        $inadimplenteChurches = $crmStats['inadimplente'];

        $recentChurches = Church::query()
            ->tap(fn ($q) => ErpChurchScope::applyToChurchQuery($q, $user))
            ->withCount(['users', 'jovensMembers', 'leaders'])
            ->orderByDesc('updated_at')
            ->limit(6)
            ->get();

        $topJovensChurches = Church::query()
            ->tap(fn ($q) => ErpChurchScope::applyToChurchQuery($q, $user))
            ->withCount('jovensMembers')
            ->orderByDesc('jovens_members_count')
            ->limit(5)
            ->get();

        $pendingRequestsCount = Schema::hasTable('igrejas_church_change_requests')
            ? ChurchChangeRequest::query()->where('status', ChurchChangeRequest::STATUS_SUBMITTED)->count()
            : 0;

        $churchesMissingLeadership = Church::query()
            ->tap(fn ($q) => ErpChurchScope::applyToChurchQuery($q, $user))
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('pastor_user_id')->orWhereNull('unijovem_leader_user_id');
            })
            ->orderBy('name')
            ->limit(8)
            ->get(['id', 'name', 'city', 'pastor_user_id', 'unijovem_leader_user_id']);

        $upcomingAnniversaries = Church::query()
            ->tap(fn ($q) => ErpChurchScope::applyToChurchQuery($q, $user))
            ->whereNotNull('foundation_date')
            ->where('is_active', true)
            ->orderBy('foundation_date')
            ->limit(6)
            ->get();

        return view('igrejas::paineldiretoria.dashboard', [
            'layout' => 'layouts.app',
            'routePrefix' => 'diretoria.igrejas',
            'totalChurches' => $totalChurches,
            'activeChurches' => $activeChurches,
            'inactiveChurches' => $inactiveChurches,
            'inadimplenteChurches' => $inadimplenteChurches,
            'growthLast12m' => $growth['last_12m'],
            'growthPrevious12m' => $growth['previous_12m'],
            'recentChurches' => $recentChurches,
            'topJovensChurches' => $topJovensChurches,
            'pendingRequestsCount' => $pendingRequestsCount,
            'churchesMissingLeadership' => $churchesMissingLeadership,
            'upcomingAnniversaries' => $upcomingAnniversaries,
        ]);
    }
}
