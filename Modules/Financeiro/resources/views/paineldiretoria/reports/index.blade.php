@extends($layout)

@section('title', 'Relatórios financeiros')

@section('content')
@php
    use Modules\Financeiro\App\Support\FinReportingPeriod;
    $in = 'rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400';
    $lb = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
    $balance = $totals['in'] - $totals['out'];
    $periodPreset = $periodPreset ?? FinReportingPeriod::PRESET_MONTH;
    $periodLabel = $periodLabel ?? '';
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('financeiro::paineldiretoria.partials.subnav', ['active' => 'reports'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Transparência</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
                    <x-icon name="chart-column" class="h-5 w-5" style="duotone" />
                </span>
                Balancete
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                Totais por categoria e por âmbito (<strong>regional JUBAF</strong> vs <strong>por igreja</strong>). Escolha o período (mês, trimestre, <strong>ano associativo mar.–fev.</strong>, ano civil, YTD ou datas livres). Exporte <strong>CSV</strong> ou <strong>PDF A4</strong> para assembleia ou arquivo. <span class="font-semibold tabular-nums text-gray-800 dark:text-gray-200">{{ $txCount }}</span> lançamento(s) no intervalo.
            </p>
            @if($periodLabel !== '')
                <p class="mt-2 text-xs font-semibold text-emerald-800 dark:text-emerald-300">Período seleccionado: {{ $periodLabel }} · {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</p>
            @endif
        </div>
        @php
            $exportQuery = array_filter([
                'period' => $periodPreset,
                'from' => $from,
                'to' => $to,
                'anchor' => $anchor ?? null,
                'year' => $year ?? null,
                'assoc_year' => $assocYear ?? null,
            ], fn ($v) => $v !== null && $v !== '');
        @endphp
        <div class="flex shrink-0 flex-wrap gap-2">
            <a href="{{ route('diretoria.financeiro.reports.export.csv', $exportQuery) }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-gray-800 shadow-sm transition hover:border-emerald-200 hover:bg-emerald-50/50 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-emerald-800">
                <x-icon name="download" class="h-4 w-4 text-emerald-600 dark:text-emerald-400" style="duotone" />
                CSV
            </a>
            <a href="{{ route('diretoria.financeiro.reports.export.pdf', $exportQuery) }}" class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50/80 px-4 py-2.5 text-sm font-bold text-emerald-900 shadow-sm transition hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-100 dark:hover:bg-emerald-900/30">
                <x-icon name="file-pdf" class="h-4 w-4 text-emerald-700 dark:text-emerald-300" style="duotone" />
                PDF A4
            </a>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Período</h2>
        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Presets para relatório perante assembleia ou encerramento contabilístico. Datas inclusivas nos totais.</p>
        <form method="get" class="mt-4 flex flex-wrap items-end gap-4">
            <div class="min-w-[12rem]">
                <label class="{{ $lb }}">Tipo de período</label>
                <select name="period" class="w-full {{ $in }}" id="fin-period-preset">
                    <option value="{{ FinReportingPeriod::PRESET_MONTH }}" @selected($periodPreset === FinReportingPeriod::PRESET_MONTH)>Mês civil</option>
                    <option value="{{ FinReportingPeriod::PRESET_QUARTER }}" @selected($periodPreset === FinReportingPeriod::PRESET_QUARTER)>Trimestre</option>
                    <option value="{{ FinReportingPeriod::PRESET_SEMESTER }}" @selected($periodPreset === FinReportingPeriod::PRESET_SEMESTER)>Semestre</option>
                    <option value="{{ FinReportingPeriod::PRESET_YEAR }}" @selected($periodPreset === FinReportingPeriod::PRESET_YEAR)>Ano civil (completo)</option>
                    <option value="{{ FinReportingPeriod::PRESET_ASSOCIATIVE_YEAR }}" @selected($periodPreset === FinReportingPeriod::PRESET_ASSOCIATIVE_YEAR)>Ano associativo (mar.–fev.)</option>
                    <option value="{{ FinReportingPeriod::PRESET_YTD }}" @selected($periodPreset === FinReportingPeriod::PRESET_YTD)>Ano a hoje (YTD)</option>
                    <option value="{{ FinReportingPeriod::PRESET_CUSTOM }}" @selected($periodPreset === FinReportingPeriod::PRESET_CUSTOM)>Personalizado (datas)</option>
                </select>
            </div>
            <div class="min-w-[9rem] fin-period-assoc-wrap">
                <label class="{{ $lb }}">Início (março)</label>
                <input type="number" name="assoc_year" min="2000" max="2099" value="{{ old('assoc_year', $assocYear ?? (\Carbon\Carbon::now()->month >= 3 ? \Carbon\Carbon::now()->year : \Carbon\Carbon::now()->year - 1)) }}" class="w-full {{ $in }} tabular-nums" title="Ano civil em que começa o ciclo (1 de março)">
            </div>
            <div class="min-w-[10rem] fin-period-anchor-wrap">
                <label class="{{ $lb }}">Referência (mês / T / sem.)</label>
                <input type="date" name="anchor" value="{{ old('anchor', $anchor ?? now()->toDateString()) }}" class="w-full {{ $in }}">
            </div>
            <div class="min-w-[7rem] fin-period-year-wrap">
                <label class="{{ $lb }}">Ano civil</label>
                <input type="number" name="year" min="2000" max="2100" value="{{ old('year', $year ?? now()->year) }}" class="w-full {{ $in }} tabular-nums">
            </div>
            <div class="min-w-[9rem] fin-period-custom-from">
                <label class="{{ $lb }}">De</label>
                <input type="date" name="from" value="{{ $from }}" class="w-full {{ $in }}">
            </div>
            <div class="min-w-[9rem] fin-period-custom-to">
                <label class="{{ $lb }}">Até</label>
                <input type="date" name="to" value="{{ $to }}" class="w-full {{ $in }}">
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800 dark:bg-slate-700 dark:hover:bg-slate-600">
                <x-icon name="arrows-rotate" class="h-4 w-4 opacity-90" style="duotone" />
                Actualizar
            </button>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sel = document.getElementById('fin-period-preset');
                const anchorWrap = document.querySelector('.fin-period-anchor-wrap');
                const yearWrap = document.querySelector('.fin-period-year-wrap');
                const assocWrap = document.querySelector('.fin-period-assoc-wrap');
                const fromEl = document.querySelector('.fin-period-custom-from');
                const toEl = document.querySelector('.fin-period-custom-to');
                if (!sel || !anchorWrap || !yearWrap || !fromEl || !toEl) return;
                function sync() {
                    const v = sel.value;
                    const showAnchor = v === '{{ FinReportingPeriod::PRESET_MONTH }}' || v === '{{ FinReportingPeriod::PRESET_QUARTER }}' || v === '{{ FinReportingPeriod::PRESET_SEMESTER }}';
                    const showYear = v === '{{ FinReportingPeriod::PRESET_YEAR }}';
                    const showAssoc = v === '{{ FinReportingPeriod::PRESET_ASSOCIATIVE_YEAR }}';
                    const showCustom = v === '{{ FinReportingPeriod::PRESET_CUSTOM }}';
                    anchorWrap.style.display = showAnchor ? 'block' : 'none';
                    yearWrap.style.display = showYear ? 'block' : 'none';
                    if (assocWrap) assocWrap.style.display = showAssoc ? 'block' : 'none';
                    fromEl.style.display = showCustom ? 'block' : 'none';
                    toEl.style.display = showCustom ? 'block' : 'none';
                }
                sel.addEventListener('change', sync);
                sync();
            });
        </script>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm ring-1 ring-emerald-500/5 dark:border-emerald-900/40 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase tracking-wide text-emerald-800/80 dark:text-emerald-400/90">Receitas</p>
            <p class="mt-2 text-2xl font-bold tabular-nums text-emerald-700 dark:text-emerald-300">R$ {{ number_format($totals['in'], 2, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-sm ring-1 ring-rose-500/5 dark:border-rose-900/40 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase tracking-wide text-rose-800/80 dark:text-rose-400/90">Despesas</p>
            <p class="mt-2 text-2xl font-bold tabular-nums text-rose-700 dark:text-rose-300">R$ {{ number_format($totals['out'], 2, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-slate-600 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Saldo</p>
            <p class="mt-2 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">R$ {{ number_format($balance, 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="border-b border-gray-100 px-5 py-4 dark:border-slate-700">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Por âmbito</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400">Soma por tipo no período — regional (tesouraria JUBAF) ou por congregação.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/90 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/90 dark:text-gray-400">
                        <th class="px-5 py-3.5">Âmbito</th>
                        <th class="px-5 py-3.5">Tipo</th>
                        <th class="px-5 py-3.5 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($scopeBreakdown as $row)
                        <tr class="transition hover:bg-emerald-50/30 dark:hover:bg-slate-900/50">
                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ \Modules\Financeiro\App\Models\FinTransaction::normalizeScopeLabel($row->scope) }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-bold {{ $row->direction === 'in' ? 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-100' : 'bg-rose-100 text-rose-900 dark:bg-rose-900/40 dark:text-rose-100' }}">
                                    {{ $row->direction === 'in' ? 'Receita' : 'Despesa' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold tabular-nums text-gray-900 dark:text-white">R$ {{ number_format((float) $row->total, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">Sem dados por âmbito neste período.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="border-b border-gray-100 px-5 py-4 dark:border-slate-700">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Por categoria</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400">Soma por tipo (receita / despesa) no período.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/90 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/90 dark:text-gray-400">
                        <th class="px-5 py-3.5">Categoria</th>
                        <th class="px-5 py-3.5">Código</th>
                        <th class="px-5 py-3.5">Grupo</th>
                        <th class="px-5 py-3.5">Tipo</th>
                        <th class="px-5 py-3.5 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($byCategory as $row)
                        @php $cat = $categories[$row->category_id] ?? null; @endphp
                        <tr class="transition hover:bg-emerald-50/30 dark:hover:bg-slate-900/50">
                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $cat->name ?? '—' }}</td>
                            <td class="px-5 py-3.5 font-mono text-xs text-gray-600 dark:text-gray-400">{{ $cat->code ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-xs text-gray-600 dark:text-gray-400">{{ $cat ? \Modules\Financeiro\App\Models\FinCategory::groupLabel($cat->group_key) : '—' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-bold {{ $row->direction === 'in' ? 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-100' : 'bg-rose-100 text-rose-900 dark:bg-rose-900/40 dark:text-rose-100' }}">
                                    {{ $row->direction === 'in' ? 'Receita' : 'Despesa' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold tabular-nums text-gray-900 dark:text-white">R$ {{ number_format((float) $row->total, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-14 text-center text-gray-500 dark:text-gray-400">
                                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 dark:bg-slate-700">
                                    <x-icon name="chart-column" class="h-6 w-6" style="duotone" />
                                </span>
                                <p class="mt-3 font-semibold text-gray-900 dark:text-white">Sem movimentos neste período</p>
                                <p class="mt-1 text-sm">Alargue as datas ou registe lançamentos na tesouraria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($churchBreakdown->isNotEmpty())
        <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Por igreja</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Apenas lançamentos com âmbito por igreja e igreja definida.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/90 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/90 dark:text-gray-400">
                            <th class="px-5 py-3.5">Igreja</th>
                            <th class="px-5 py-3.5 text-right">Receitas</th>
                            <th class="px-5 py-3.5 text-right">Despesas</th>
                            <th class="px-5 py-3.5 text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @foreach($churchBreakdown as $row)
                            <tr class="transition hover:bg-emerald-50/30 dark:hover:bg-slate-900/50">
                                <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $row['name'] }}</td>
                                <td class="px-5 py-3.5 text-right tabular-nums text-emerald-700 dark:text-emerald-300">R$ {{ number_format($row['in'], 2, ',', '.') }}</td>
                                <td class="px-5 py-3.5 text-right tabular-nums text-rose-700 dark:text-rose-300">R$ {{ number_format($row['out'], 2, ',', '.') }}</td>
                                <td class="px-5 py-3.5 text-right font-semibold tabular-nums text-gray-900 dark:text-white">R$ {{ number_format($row['balance'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
