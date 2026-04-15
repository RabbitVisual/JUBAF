<?php

namespace Modules\PainelDiretoria\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Financeiro\App\Services\FinanceiroService;
use Modules\Igrejas\App\Repositories\ChurchRepository;
use Modules\PainelJovens\App\Services\CensoService;
use Modules\Secretaria\App\Services\AtaWorkflowService;

class DiretoriaDashboardController extends Controller
{
    public function __construct(
        protected ChurchRepository $churchRepository,
        protected CensoService $censoService,
        protected FinanceiroService $financeiroService,
        protected AtaWorkflowService $ataWorkflowService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user()->loadMissing(['roles', 'church']);

        $churchStats = module_enabled('Igrejas') ? $this->churchRepository->statsForUser($user) : null;
        $growthStats = module_enabled('Igrejas') ? $this->churchRepository->growthStatsForUser($user) : null;

        $financeKpis = null;
        $latestTransactions = collect();
        if (module_enabled('Financeiro') && $user->can('financeiro.dashboard.view')) {
            $financeKpis = $this->financeiroService->monthKpisForUser($user);
            $latestTransactions = $this->financeiroService->latestTransactionsForUser($user, 8);
        }

        $topSectors = module_enabled('PainelJovens')
            ? $this->censoService->topSectorsByYouthCountForUser($user, 5)
            : [];

        $pendingMinutes = collect();
        $pendingMinutesCount = 0;
        if ($user->can('secretaria.minutes.view')) {
            $pendingMinutesCount = $this->ataWorkflowService->pendingMinutesCount($user);
            $pendingMinutes = $this->ataWorkflowService->pendingMinutesForUser($user, 8);
        }

        return view('paineldiretoria::dashboard', [
            'user' => $user,
            'churchStats' => $churchStats,
            'growthStats' => $growthStats,
            'financeKpis' => $financeKpis,
            'latestTransactions' => $latestTransactions,
            'topSectors' => $topSectors,
            'pendingMinutes' => $pendingMinutes,
            'pendingMinutesCount' => $pendingMinutesCount,
        ]);
    }
}
