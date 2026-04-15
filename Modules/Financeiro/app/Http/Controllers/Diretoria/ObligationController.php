<?php

namespace Modules\Financeiro\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use App\Support\ErpChurchScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Financeiro\App\Models\FinObligation;
use Modules\Financeiro\App\Services\FinObligationGenerator;
use Modules\Financeiro\App\Support\FinReportingPeriod;

class ObligationController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', FinObligation::class);

        $user = $request->user();
        $yearFilter = $request->filled('year') ? (int) $request->input('year') : null;
        $statsYear = $yearFilter ?? FinReportingPeriod::defaultAssociativeStartYear();

        $q = FinObligation::query()->with(['church', 'finTransaction'])->orderByDesc('assoc_start_year')->orderBy('church_id');
        if ($user) {
            ErpChurchScope::applyToFinObligationQuery($q, $user);
        }
        if ($yearFilter !== null) {
            $q->where('assoc_start_year', $yearFilter);
        }
        if ($request->filled('status')) {
            $q->where('status', (string) $request->input('status'));
        }

        $obligations = $q->paginate(30)->withQueryString();

        $statsBase = FinObligation::query();
        if ($user) {
            ErpChurchScope::applyToFinObligationQuery($statsBase, $user);
        }
        $statsBase->where('assoc_start_year', $statsYear);
        $obligationStats = [
            'year' => $statsYear,
            'pending' => (clone $statsBase)->where('status', FinObligation::STATUS_PENDING)->count(),
            'paid' => (clone $statsBase)->where('status', FinObligation::STATUS_PAID)->count(),
            'total' => (clone $statsBase)->count(),
        ];

        return view('financeiro::paineldiretoria.obligations.index', [
            'layout' => 'layouts.app',
            'routePrefix' => 'diretoria.financeiro',
            'obligations' => $obligations,
            'filters' => $request->only(['year', 'status']),
            'defaultAssocYear' => FinReportingPeriod::defaultAssociativeStartYear(),
            'obligationStats' => $obligationStats,
        ]);
    }

    public function generate(Request $request, FinObligationGenerator $generator): RedirectResponse
    {
        $this->authorize('generate', FinObligation::class);

        $validated = $request->validate([
            'assoc_start_year' => ['nullable', 'integer', 'min:2000', 'max:2099'],
        ]);

        $year = isset($validated['assoc_start_year'])
            ? (int) $validated['assoc_start_year']
            : FinReportingPeriod::defaultAssociativeStartYear();

        try {
            $result = $generator->generateForAssociativeYear($year, false);
            $created = $result['created'];
            $assocYear = $result['assoc_start_year'];
            if ($created === 0) {
                $msg = "Não há cotas novas para o ano que começa em {$assocYear}: todas as igrejas activas já têm registo (ou não há igrejas activas).";
            } else {
                $msg = "Foram criadas {$created} cota(s) para o ano associativo que começa em {$assocYear}.";
            }

            return redirect()
                ->route('diretoria.financeiro.obligations.index', $request->only(['year', 'status']))
                ->with('success', $msg);
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível gerar as cotas. Se o problema continuar, contacte o suporte técnico.');
        }
    }
}
