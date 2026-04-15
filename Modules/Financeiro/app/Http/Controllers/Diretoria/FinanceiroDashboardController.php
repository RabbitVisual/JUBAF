<?php

namespace Modules\Financeiro\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use App\Support\ErpChurchScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Modules\Avisos\App\Models\Aviso;
use Modules\Blog\App\Models\BlogPost;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinExpenseRequest;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Igrejas\App\Models\Church;

class FinanceiroDashboardController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->can('financeiro.dashboard.view'), 403);

        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $ytdStart = Carbon::now()->startOfYear();

        $user = $request->user();
        $txBase = FinTransaction::query();
        if ($user) {
            ErpChurchScope::applyToFinTransactionQuery($txBase, $user);
        }

        $in = (float) (clone $txBase)
            ->where('direction', 'in')
            ->whereBetween('occurred_on', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $out = (float) (clone $txBase)
            ->where('direction', 'out')
            ->whereBetween('occurred_on', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $ytdIn = (float) (clone $txBase)
            ->where('direction', 'in')
            ->whereBetween('occurred_on', [$ytdStart->toDateString(), $end->toDateString()])
            ->sum('amount');

        $ytdOut = (float) (clone $txBase)
            ->where('direction', 'out')
            ->whereBetween('occurred_on', [$ytdStart->toDateString(), $end->toDateString()])
            ->sum('amount');

        $monthRegionalIn = (float) (clone $txBase)
            ->where('direction', 'in')
            ->whereIn('scope', [FinTransaction::SCOPE_REGIONAL, FinTransaction::SCOPE_LEGACY_NACIONAL])
            ->whereBetween('occurred_on', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $monthRegionalOut = (float) (clone $txBase)
            ->where('direction', 'out')
            ->whereIn('scope', [FinTransaction::SCOPE_REGIONAL, FinTransaction::SCOPE_LEGACY_NACIONAL])
            ->whereBetween('occurred_on', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $monthChurchIn = (float) (clone $txBase)
            ->where('direction', 'in')
            ->where('scope', FinTransaction::SCOPE_CHURCH)
            ->whereBetween('occurred_on', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $monthChurchOut = (float) (clone $txBase)
            ->where('direction', 'out')
            ->where('scope', FinTransaction::SCOPE_CHURCH)
            ->whereBetween('occurred_on', [$start->toDateString(), $end->toDateString()])
            ->sum('amount');

        $pendingExpenses = FinExpenseRequest::query()
            ->whereIn('status', [FinExpenseRequest::STATUS_SUBMITTED, FinExpenseRequest::STATUS_APPROVED])
            ->count();

        $monthTxCount = (int) (clone $txBase)
            ->whereBetween('occurred_on', [$start->toDateString(), $end->toDateString()])
            ->count();

        $ytdTxCount = (int) (clone $txBase)
            ->whereBetween('occurred_on', [$ytdStart->toDateString(), $end->toDateString()])
            ->count();

        $priorMonthStart = Carbon::now()->subMonthNoOverflow()->startOfMonth();
        $priorMonthEnd = Carbon::now()->subMonthNoOverflow()->endOfMonth();
        $priorIn = (float) (clone $txBase)
            ->where('direction', 'in')
            ->whereBetween('occurred_on', [$priorMonthStart->toDateString(), $priorMonthEnd->toDateString()])
            ->sum('amount');
        $priorOut = (float) (clone $txBase)
            ->where('direction', 'out')
            ->whereBetween('occurred_on', [$priorMonthStart->toDateString(), $priorMonthEnd->toDateString()])
            ->sum('amount');

        $topCategoriesMonth = (clone $txBase)
            ->selectRaw('category_id, SUM(amount) as total')
            ->whereBetween('occurred_on', [$start->toDateString(), $end->toDateString()])
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $categoryIds = $topCategoriesMonth->pluck('category_id')->filter()->unique()->values();
        $categoryNames = $categoryIds->isNotEmpty()
            ? FinCategory::query()->whereIn('id', $categoryIds)->pluck('name', 'id')
            : collect();
        $topCategoriesMonth = $topCategoriesMonth->map(function ($row) use ($categoryNames) {
            return [
                'category_id' => $row->category_id,
                'name' => (string) $categoryNames->get($row->category_id, '—'),
                'total' => (float) $row->total,
            ];
        });

        $expenseByStatus = FinExpenseRequest::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->map(fn ($c) => (int) $c)
            ->all();

        $recent = (clone $txBase)
            ->with(['category', 'church', 'creator'])
            ->orderByDesc('occurred_on')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $openAvisosCount = 0;
        if (module_enabled('Avisos') && class_exists(Aviso::class)) {
            $openAvisosCount = (int) Aviso::query()->where('ativo', true)->count();
        }

        $gatewayMonthPaidCount = 0;
        $gatewayMonthPaidTotal = 0.0;
        $gatewayPendingCount = 0;
        $gatewayEnabled = false;
        if (module_enabled('Gateway')) {
            $gwClass = \Modules\Gateway\App\Models\GatewayPayment::class;
            if (class_exists($gwClass)) {
                $gatewayEnabled = true;
                $gatewayMonthPaidCount = (int) $gwClass::query()
                    ->where('status', $gwClass::STATUS_PAID)
                    ->whereNotNull('paid_at')
                    ->whereBetween('paid_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
                    ->count();
                $gatewayMonthPaidTotal = (float) $gwClass::query()
                    ->where('status', $gwClass::STATUS_PAID)
                    ->whereNotNull('paid_at')
                    ->whereBetween('paid_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
                    ->sum('amount');
                $gatewayPendingCount = (int) $gwClass::query()
                    ->where('status', $gwClass::STATUS_PENDING)
                    ->count();
            }
        }

        $maxTopCat = $topCategoriesMonth->max('total') ?: 1.0;

        $momInPct = $priorIn > 0 ? round((($in - $priorIn) / $priorIn) * 100, 1) : null;
        $momOutPct = $priorOut > 0 ? round((($out - $priorOut) / $priorOut) * 100, 1) : null;

        return view('financeiro::paineldiretoria.dashboard', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.financeiro',
            'monthIn' => $in,
            'monthOut' => $out,
            'balance' => $in - $out,
            'priorMonthIn' => $priorIn,
            'priorMonthOut' => $priorOut,
            'momInPct' => $momInPct,
            'momOutPct' => $momOutPct,
            'ytdIn' => $ytdIn,
            'ytdOut' => $ytdOut,
            'ytdBalance' => $ytdIn - $ytdOut,
            'monthRegionalIn' => $monthRegionalIn,
            'monthRegionalOut' => $monthRegionalOut,
            'monthChurchIn' => $monthChurchIn,
            'monthChurchOut' => $monthChurchOut,
            'monthTxCount' => $monthTxCount,
            'ytdTxCount' => $ytdTxCount,
            'topCategoriesMonth' => $topCategoriesMonth,
            'maxTopCategoryTotal' => $maxTopCat,
            'pendingExpenses' => $pendingExpenses,
            'expenseByStatus' => $expenseByStatus,
            'recent' => $recent,
            'quickLinks' => $this->quickLinks($request),
            'openAvisosCount' => $openAvisosCount,
            'estatutoPostUrl' => $this->estatutoBlogUrl(),
            'gatewayEnabled' => $gatewayEnabled,
            'gatewayMonthPaidCount' => $gatewayMonthPaidCount,
            'gatewayMonthPaidTotal' => $gatewayMonthPaidTotal,
            'gatewayPendingCount' => $gatewayPendingCount,
            'dashboardMonthLabel' => Carbon::now()->translatedFormat('F \d\e Y'),
        ]);
    }

    /**
     * Atalhos para módulos que o tesoureiro usa no dia a dia (calendário, avisos, assembleia, cadastro).
     *
     * @return list<array{label: string, href: string, hint: string, icon: string}> (até 6 entradas)
     */
    private function quickLinks(Request $request): array
    {
        $user = $request->user();
        if (! $user) {
            return [];
        }

        $links = [];

        if (Route::has('diretoria.calendario.dashboard') && $user->can('calendario.events.view')) {
            $links[] = [
                'label' => 'Calendário',
                'href' => route('diretoria.calendario.dashboard'),
                'hint' => 'Eventos e inscrições.',
                'icon' => 'calendar-days',
            ];
        }

        if (Route::has('diretoria.financeiro.obligations.index') && $user->can('financeiro.obligations.view')) {
            $links[] = [
                'label' => 'Cotas',
                'href' => route('diretoria.financeiro.obligations.index'),
                'hint' => 'Por igreja e ano associativo.',
                'icon' => 'coins',
            ];
        }

        if (Route::has('diretoria.igrejas.dashboard') && $user->can('viewAny', Church::class)) {
            $links[] = [
                'label' => 'Igrejas (ASBAF)',
                'href' => route('diretoria.igrejas.dashboard'),
                'hint' => 'Congregações e movimentos por igreja.',
                'icon' => 'church',
            ];
        }

        if (Route::has('diretoria.secretaria.dashboard')) {
            $links[] = [
                'label' => 'Secretaria',
                'href' => route('diretoria.secretaria.dashboard'),
                'hint' => 'Atas e assembleia.',
                'icon' => 'file-signature',
            ];
        }

        if (Route::has('diretoria.avisos.index') && $user->can('viewAny', Aviso::class)) {
            $links[] = [
                'label' => 'Avisos',
                'href' => route('diretoria.avisos.index'),
                'hint' => 'Comunicar prazos ao campo.',
                'icon' => 'bullhorn',
            ];
        }

        if (Route::has('diretoria.blog.index') && $user->can('viewAny', BlogPost::class)) {
            $links[] = [
                'label' => 'Blog',
                'href' => route('diretoria.blog.index'),
                'hint' => 'Textos institucionais e transparência.',
                'icon' => 'newspaper',
            ];
        }

        if (Route::has('diretoria.notificacoes.index')) {
            $links[] = [
                'label' => 'Notificações',
                'href' => route('diretoria.notificacoes.index'),
                'hint' => 'Alertas internos.',
                'icon' => 'bell',
            ];
        }

        if (Route::has('diretoria.chat.index')) {
            $links[] = [
                'label' => 'Chat',
                'href' => route('diretoria.chat.index'),
                'hint' => 'Equipa da diretoria.',
                'icon' => 'comments',
            ];
        }

        if (Route::has('diretoria.talentos.dashboard')
            && ($user->can('talentos.directory.view') || $user->can('talentos.assignments.view'))) {
            $links[] = [
                'label' => 'Talentos',
                'href' => route('diretoria.talentos.dashboard'),
                'hint' => 'Voluntários em eventos.',
                'icon' => 'users',
            ];
        }

        if (Route::has('diretoria.homepage.index') && $user->can('homepage.edit')) {
            $links[] = [
                'label' => 'Homepage',
                'href' => route('diretoria.homepage.index'),
                'hint' => 'Site público.',
                'icon' => 'house',
            ];
        }

        return array_slice($links, 0, 6);
    }

    private function estatutoBlogUrl(): ?string
    {
        if (! module_enabled('Blog') || ! class_exists(BlogPost::class)) {
            return null;
        }

        $post = BlogPost::query()->where('slug', 'estatuto-da-jubaf-capitulos-i-a-vi')->first();

        return $post ? route('blog.show', $post->slug) : null;
    }
}
