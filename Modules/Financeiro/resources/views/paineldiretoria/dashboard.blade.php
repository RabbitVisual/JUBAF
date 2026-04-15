@extends($layout)

@section('title', 'Tesouraria JUBAF')

@section('content')
@php
    $expenseLabels = [
        'draft' => 'Rascunhos',
        'submitted' => 'Submetidos',
        'approved' => 'Aprovados (aguardam pagamento)',
        'rejected' => 'Recusados',
        'paid' => 'Pagos',
    ];
    $regionalTotal = $monthRegionalIn + $monthRegionalOut;
    $churchTotal = $monthChurchIn + $monthChurchOut;
    $scopeDenom = max($regionalTotal, $churchTotal, 1);
    $regionalPct = round(($regionalTotal / $scopeDenom) * 100);
    $churchPct = round(($churchTotal / $scopeDenom) * 100);
@endphp

<div class="mx-auto max-w-7xl space-y-8 pb-12">
    @include('financeiro::paineldiretoria.partials.subnav', ['active' => 'dashboard'])

    {{-- Hero + identidade --}}
    <div class="relative overflow-hidden rounded-3xl border border-emerald-200/70 bg-linear-to-br from-white via-emerald-50/50 to-teal-50/40 shadow-xl shadow-emerald-900/10 dark:border-emerald-900/40 dark:from-slate-900 dark:via-emerald-950/25 dark:to-slate-900">
        <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-emerald-400/20 blur-3xl dark:bg-emerald-500/10" aria-hidden="true"></div>
        <div class="pointer-events-none absolute bottom-0 left-1/3 h-48 w-48 rounded-full bg-teal-300/15 blur-2xl dark:bg-teal-600/10" aria-hidden="true"></div>
        <div class="relative grid gap-8 p-6 sm:p-8 lg:grid-cols-[1fr_auto] lg:items-center">
            <div class="min-w-0 space-y-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200/80 bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-emerald-800 shadow-sm dark:border-emerald-800 dark:bg-slate-800/90 dark:text-emerald-300">
                        <x-icon name="calendar-days" class="h-3.5 w-3.5" style="duotone" />
                        {{ $dashboardMonthLabel ?? now()->translatedFormat('F \d\e Y') }}
                    </span>
                    @if($monthTxCount > 0)
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">{{ $monthTxCount }} lançamento(s) no mês</span>
                    @endif
                </div>
                <div class="flex flex-wrap items-start gap-4">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="JUBAF" class="h-11 w-auto shrink-0 dark:brightness-110" width="140" height="44" loading="eager">
                    <div class="min-w-0">
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                            Painel da tesouraria
                        </h1>
                        <p class="mt-1 max-w-xl text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                            Lançamentos, reembolsos e relatórios num só lugar — com ligação ao pagamento online quando estiver activo.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-stretch gap-3 sm:flex-row lg:flex-col lg:items-end">
                @can('create', \Modules\Financeiro\App\Models\FinTransaction::class)
                    <a href="{{ route('diretoria.financeiro.transactions.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-600/30 transition hover:bg-emerald-700">
                        <x-icon name="plus" class="h-4 w-4" style="solid" />
                        Novo lançamento
                    </a>
                @endcan
                <div class="flex flex-wrap gap-2">
                    @can('financeiro.reports.view')
                        <a href="{{ route('diretoria.financeiro.reports.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white/90 px-3 py-2 text-xs font-bold text-slate-800 shadow-sm hover:border-emerald-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            <x-icon name="chart-column" class="h-3.5 w-3.5 text-emerald-600" style="duotone" />
                            Balancete
                        </a>
                        <a href="{{ route('diretoria.financeiro.reports.export.pdf', ['period' => 'month']) }}" class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50/90 px-3 py-2 text-xs font-bold text-emerald-900 hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-100">
                            <x-icon name="file-pdf" class="h-3.5 w-3.5" style="duotone" />
                            PDF mês
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- KPIs principais --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm ring-1 ring-emerald-500/5 dark:border-emerald-900/40 dark:bg-slate-800">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wide text-emerald-800 dark:text-emerald-300">Receitas (mês)</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-emerald-700 dark:text-emerald-300">R$ {{ number_format($monthIn, 2, ',', '.') }}</p>
                    @if($momInPct !== null)
                        <p class="mt-1 text-xs font-semibold {{ $momInPct >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $momInPct >= 0 ? '+' : '' }}{{ number_format($momInPct, 1, ',', '.') }}% vs mês anterior
                        </p>
                    @endif
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                    <x-icon name="chart-line" class="h-5 w-5" style="duotone" />
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-sm dark:border-rose-900/40 dark:bg-slate-800">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wide text-rose-800 dark:text-rose-300">Despesas (mês)</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-rose-700 dark:text-rose-300">R$ {{ number_format($monthOut, 2, ',', '.') }}</p>
                    @if($momOutPct !== null)
                        <p class="mt-1 text-xs font-semibold {{ $momOutPct <= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-300' }}">
                            {{ $momOutPct >= 0 ? '+' : '' }}{{ number_format($momOutPct, 1, ',', '.') }}% vs mês anterior
                        </p>
                    @endif
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300">
                    <x-icon name="arrow-down" class="h-5 w-5" style="duotone" />
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-600 dark:bg-slate-800">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">Saldo (mês)</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">R$ {{ number_format($balance, 2, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Receitas − despesas</p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                    <x-icon name="scale-balanced" class="h-5 w-5" style="duotone" />
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm ring-1 ring-amber-500/10 dark:border-amber-900/40 dark:bg-slate-800">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wide text-amber-800 dark:text-amber-300">Reembolsos em fila</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-amber-700 dark:text-amber-300">{{ $pendingExpenses }}</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Submetidos ou aprovados</p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200">
                    <x-icon name="clock" class="h-5 w-5" style="duotone" />
                </div>
            </div>
        </div>
    </div>

    @if(!empty($gatewayEnabled) && $gatewayEnabled)
        <div class="rounded-2xl border border-cyan-200/80 bg-linear-to-r from-cyan-50 to-white p-5 shadow-sm dark:border-cyan-900/40 dark:from-cyan-950/40 dark:to-slate-800">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-3">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-200">
                        <x-module-icon module="Gateway" class="h-7 w-7" />
                    </span>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-cyan-800 dark:text-cyan-300">Gateway · Pagamentos online</p>
                        <p class="mt-1 text-sm text-slate-700 dark:text-slate-300">
                            <strong class="tabular-nums">{{ $gatewayMonthPaidCount }}</strong> pago(s) no mês · total <strong class="tabular-nums text-cyan-800 dark:text-cyan-200">R$ {{ number_format($gatewayMonthPaidTotal, 2, ',', '.') }}</strong>
                            @if(($gatewayPendingCount ?? 0) > 0)
                                · <span class="text-amber-700 dark:text-amber-300">{{ $gatewayPendingCount }} pendente(s)</span>
                            @endif
                        </p>
                    </div>
                </div>
                @if(Route::has('diretoria.gateway.dashboard') && auth()->user()?->can('gateway.dashboard.view'))
                    <a href="{{ route('diretoria.gateway.dashboard') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-cyan-200 bg-white px-4 py-2.5 text-sm font-bold text-cyan-900 shadow-sm transition hover:bg-cyan-50 dark:border-cyan-800 dark:bg-slate-900 dark:text-cyan-100">
                        Abrir Gateway
                        <x-icon name="arrow-right" class="h-3.5 w-3.5" style="duotone" />
                    </a>
                @endif
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        {{-- Coluna principal --}}
        <div class="space-y-6 xl:col-span-8">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Acumulado no ano (YTD)</h2>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Desde 1 de janeiro até hoje.</p>
                    <dl class="mt-4 grid grid-cols-3 gap-2">
                        <div class="rounded-xl bg-emerald-50/90 p-3 dark:bg-emerald-950/35">
                            <dt class="text-[10px] font-bold uppercase text-emerald-800 dark:text-emerald-300">Receitas</dt>
                            <dd class="mt-1 text-base font-bold tabular-nums text-emerald-800 dark:text-emerald-200">R$ {{ number_format($ytdIn, 2, ',', '.') }}</dd>
                        </div>
                        <div class="rounded-xl bg-rose-50/90 p-3 dark:bg-rose-950/35">
                            <dt class="text-[10px] font-bold uppercase text-rose-800 dark:text-rose-300">Despesas</dt>
                            <dd class="mt-1 text-base font-bold tabular-nums text-rose-800 dark:text-rose-200">R$ {{ number_format($ytdOut, 2, ',', '.') }}</dd>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-900/50">
                            <dt class="text-[10px] font-bold uppercase text-slate-600 dark:text-slate-400">Saldo</dt>
                            <dd class="mt-1 text-base font-bold tabular-nums text-slate-900 dark:text-white">R$ {{ number_format($ytdBalance, 2, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="rounded-2xl border border-teal-200/60 bg-linear-to-br from-white to-teal-50/50 p-5 shadow-sm dark:border-teal-900/40 dark:from-slate-800 dark:to-teal-950/25">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Este mês por âmbito</h2>
                    <p class="mt-0.5 text-xs text-slate-600 dark:text-slate-400">Volume relativo de movimentação (entradas + saídas).</p>
                    <div class="mt-4 space-y-3">
                        <div>
                            <div class="mb-1 flex justify-between text-[11px] font-semibold text-slate-600 dark:text-slate-400">
                                <span>Regional (JUBAF)</span>
                                <span class="tabular-nums">{{ $regionalPct }}%</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700">
                                <div class="h-full rounded-full bg-emerald-500 transition-all dark:bg-emerald-500" style="width: {{ $regionalPct }}%"></div>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">In R$ {{ number_format($monthRegionalIn, 2, ',', '.') }} · Out R$ {{ number_format($monthRegionalOut, 2, ',', '.') }}</p>
                        </div>
                        <div>
                            <div class="mb-1 flex justify-between text-[11px] font-semibold text-slate-600 dark:text-slate-400">
                                <span>Por igreja</span>
                                <span class="tabular-nums">{{ $churchPct }}%</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700">
                                <div class="h-full rounded-full bg-cyan-500 transition-all dark:bg-cyan-600" style="width: {{ $churchPct }}%"></div>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">In R$ {{ number_format($monthChurchIn, 2, ',', '.') }} · Out R$ {{ number_format($monthChurchOut, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Top categorias (mês)</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Maiores totais no mês corrente.</p>
                    </div>
                </div>
                @if($topCategoriesMonth->isEmpty())
                    <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">Sem dados por categoria neste mês.</p>
                @else
                    <ul class="mt-4 space-y-3">
                        @foreach($topCategoriesMonth as $tc)
                            @php $pct = $maxTopCategoryTotal > 0 ? round(($tc['total'] / $maxTopCategoryTotal) * 100) : 0; @endphp
                            <li>
                                <div class="flex items-center justify-between gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    <span class="truncate">{{ $tc['name'] }}</span>
                                    <span class="shrink-0 tabular-nums text-slate-900 dark:text-white">R$ {{ number_format($tc['total'], 2, ',', '.') }}</span>
                                </div>
                                <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700">
                                    <div class="h-full rounded-full bg-linear-to-r from-emerald-500 to-teal-500" style="width: {{ $pct }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Coluna lateral --}}
        <div class="space-y-6 xl:col-span-4">
            @if(count($quickLinks) > 0)
                <details class="group rounded-2xl border border-slate-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <summary class="cursor-pointer list-none p-5 text-sm font-bold text-slate-900 marker:content-none dark:text-white [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center justify-between gap-2">
                            <span>Outros módulos</span>
                            <x-icon name="chevron-down" class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" style="duotone" />
                        </span>
                        <span class="mt-1 block text-xs font-normal text-slate-500 dark:text-slate-400">Calendário, cotas, igrejas… (opcional)</span>
                    </summary>
                    <ul class="space-y-2 border-t border-slate-100 px-5 pb-5 pt-3 dark:border-slate-700">
                        @foreach($quickLinks as $link)
                            <li>
                                <a href="{{ $link['href'] }}" class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/50 p-2.5 text-sm font-semibold text-slate-800 transition hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-slate-700 dark:bg-slate-900/40 dark:text-white dark:hover:border-emerald-800">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-emerald-600 shadow-sm dark:bg-slate-800 dark:text-emerald-400">
                                        <x-icon name="{{ $link['icon'] }}" class="h-4 w-4" style="duotone" />
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate">{{ $link['label'] }}</span>
                                        <span class="block truncate text-[11px] font-normal text-slate-500 dark:text-slate-400">{{ $link['hint'] }}</span>
                                    </span>
                                    <x-icon name="chevron-right" class="h-3.5 w-3.5 shrink-0 text-slate-400" style="duotone" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>
            @endif

            @if($estatutoPostUrl)
                <div class="rounded-2xl border border-indigo-100 bg-indigo-50/50 p-4 dark:border-indigo-900/50 dark:bg-indigo-950/25">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-indigo-800 dark:text-indigo-300">Referência legal</p>
                    <p class="mt-2 text-sm text-slate-700 dark:text-slate-300">Estatuto e fins da JUBAF.</p>
                    <a href="{{ $estatutoPostUrl }}" class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-indigo-700 hover:underline dark:text-indigo-400">
                        Abrir no blog
                        <x-icon name="arrow-up-right-from-square" class="h-3.5 w-3.5" style="duotone" />
                    </a>
                </div>
            @endif

            @if($openAvisosCount > 0)
                <div class="rounded-2xl border border-amber-100 bg-amber-50/60 p-4 dark:border-amber-900/40 dark:bg-amber-950/20">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-amber-900 dark:text-amber-300">Avisos activos</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-amber-900 dark:text-amber-200">{{ $openAvisosCount }}</p>
                    @if(Route::has('diretoria.avisos.index') && auth()->user()?->can('viewAny', \Modules\Avisos\App\Models\Aviso::class))
                        <a href="{{ route('diretoria.avisos.index') }}" class="mt-2 inline-flex text-sm font-bold text-amber-800 hover:underline dark:text-amber-300">Gerir avisos</a>
                    @endif
                </div>
            @endif

            <details class="rounded-2xl border border-slate-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <summary class="cursor-pointer list-none p-4 text-sm font-bold text-slate-900 marker:content-none dark:text-white [&::-webkit-details-marker]:hidden">
                    Dicas para assembleia
                </summary>
                <ol class="list-decimal space-y-2 border-t border-slate-100 px-4 py-3 pl-7 text-xs leading-relaxed text-slate-600 dark:border-slate-700 dark:text-slate-400">
                    <li>Lançamentos com categoria e âmbito correctos.</li>
                    <li>Reembolsos com comprovativos arquivados.</li>
                    <li>Balancete (CSV ou PDF) antes da reunião.</li>
                </ol>
            </details>
        </div>
    </div>

    @if(!empty($expenseByStatus))
        <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white">Pedidos de reembolso</h2>
                @can('viewAny', \Modules\Financeiro\App\Models\FinExpenseRequest::class)
                    <a href="{{ route('diretoria.financeiro.expense-requests.index') }}" class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Ver fila completa</a>
                @endcan
            </div>
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                @foreach($expenseLabels as $key => $label)
                    @if(isset($expenseByStatus[$key]))
                        <div class="rounded-xl border border-slate-100 bg-slate-50/80 px-3 py-3 dark:border-slate-700 dark:bg-slate-900/50">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $label }}</p>
                            <p class="mt-1 text-xl font-bold tabular-nums text-slate-900 dark:text-white">{{ $expenseByStatus[$key] }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white">Últimos lançamentos</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Até 8 movimentos mais recentes</p>
            </div>
            @can('viewAny', \Modules\Financeiro\App\Models\FinTransaction::class)
                <a href="{{ route('diretoria.financeiro.transactions.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-emerald-700 hover:gap-2 dark:text-emerald-400">
                    Ver todos
                    <x-icon name="arrow-right" class="h-3.5 w-3.5" style="duotone" />
                </a>
            @endcan
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/90 text-left text-xs font-bold uppercase tracking-wide text-slate-500 dark:bg-slate-900/90 dark:text-slate-400">
                        <th class="px-5 py-3.5">Data</th>
                        <th class="px-5 py-3.5">Categoria</th>
                        <th class="px-5 py-3.5">Âmbito</th>
                        <th class="px-5 py-3.5">Origem</th>
                        <th class="px-5 py-3.5">Tipo</th>
                        <th class="px-5 py-3.5 text-right">Valor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($recent as $row)
                        <tr class="transition hover:bg-emerald-50/40 dark:hover:bg-slate-900/50">
                            <td class="whitespace-nowrap px-5 py-3.5 text-slate-700 dark:text-slate-300">{{ $row->occurred_on->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5 font-medium text-slate-900 dark:text-white">{{ $row->category?->name ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-400">
                                <span class="inline-flex rounded-lg bg-slate-100 px-2 py-0.5 text-[11px] font-bold text-slate-800 dark:bg-slate-900 dark:text-slate-200">{{ $row->scopeLabel() }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 dark:text-slate-400">
                                @if($row->isFromGateway())
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-cyan-100 px-2 py-0.5 text-[11px] font-bold text-cyan-900 dark:bg-cyan-900/40 dark:text-cyan-100">
                                        <x-icon name="credit-card" class="h-3 w-3" style="duotone" />
                                        Gateway
                                    </span>
                                @else
                                    <span class="text-xs">{{ \Modules\Financeiro\App\Models\FinTransaction::sourceLabel($row->source) }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-bold {{ $row->direction === 'in' ? 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-100' : 'bg-rose-100 text-rose-900 dark:bg-rose-900/40 dark:text-rose-100' }}">
                                    {{ $row->direction === 'in' ? 'Receita' : 'Despesa' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold tabular-nums text-slate-900 dark:text-white">R$ {{ number_format((float) $row->amount, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-700">
                                        <x-icon name="sack-dollar" class="h-7 w-7" style="duotone" />
                                    </span>
                                    <p class="mt-4 font-semibold text-slate-900 dark:text-white">Ainda sem lançamentos</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Comece por registar uma receita ou despesa.</p>
                                    @can('create', \Modules\Financeiro\App\Models\FinTransaction::class)
                                        <a href="{{ route('diretoria.financeiro.transactions.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">
                                            Criar lançamento
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
