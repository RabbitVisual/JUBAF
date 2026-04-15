@extends('layouts.app')

@section('title', 'Dashboard Executivo')

@php
    $balance = $financeKpis['month_balance'] ?? null;
@endphp

@section('content')
    <div class="mx-auto max-w-7xl space-y-6 p-4 sm:p-6 lg:p-8">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Painel da diretoria</p>
                    <h1 class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">Torre de controle institucional</h1>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                        Consolidação executiva de finanças, demografia e pendencias da secretaria.
                    </p>
                </div>
                @if ($user->restrictsChurchDirectoryToSector())
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                        Escopo setorial ativo
                    </span>
                @endif
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @if ($financeKpis)
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Receita do mes</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-700 dark:text-emerald-400">R$ {{ number_format((float) ($financeKpis['month_in'] ?? 0), 2, ',', '.') }}</p>
                    <div class="mt-3 h-12" data-kpi-chart="finance-in"></div>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Despesa do mes</p>
                    <p class="mt-2 text-2xl font-bold text-rose-700 dark:text-rose-400">R$ {{ number_format((float) ($financeKpis['month_out'] ?? 0), 2, ',', '.') }}</p>
                    <div class="mt-3 h-12" data-kpi-chart="finance-out"></div>
                </article>
            @else
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900 sm:col-span-2">
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Bloco financeiro indisponivel</p>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Sem permissao `financeiro.dashboard.view` para consolidacao de caixa.</p>
                </article>
            @endif
            @if ($financeKpis)
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Saldo do mes</p>
                    <p class="mt-2 text-2xl font-bold {{ ($balance ?? 0) >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }}">
                        R$ {{ number_format((float) ($balance ?? 0), 2, ',', '.') }}
                    </p>
                    <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Receitas menos despesas no periodo corrente.</p>
                </article>
            @endif
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Atas pendentes</p>
                <p class="mt-2 text-2xl font-bold text-indigo-700 dark:text-indigo-400">{{ $pendingMinutesCount }}</p>
                <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Itens aguardando aprovacao do executivo.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <article class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">Ultimas transacoes</h2>
                    @if (Route::has('diretoria.financeiro.transactions.index') && auth()->user()->can('financeiro.transactions.view'))
                        <a href="{{ route('diretoria.financeiro.transactions.index') }}" class="text-xs font-semibold text-indigo-700 hover:underline dark:text-indigo-300">Abrir tesouraria</a>
                    @endif
                </div>
                @if ($financeKpis)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-700" data-ui-datatable>
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                <th class="px-3 py-2">Data</th>
                                <th class="px-3 py-2">Descricao</th>
                                <th class="px-3 py-2">Escopo</th>
                                <th class="px-3 py-2 text-right">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($latestTransactions as $tx)
                                <tr>
                                    <td class="px-3 py-2 text-slate-600 dark:text-slate-300">{{ optional($tx->occurred_on)->format('d/m/Y') }}</td>
                                    <td class="px-3 py-2 text-slate-900 dark:text-slate-100">{{ $tx->description ?: ($tx->category->name ?? 'Sem descricao') }}</td>
                                    <td class="px-3 py-2 text-slate-600 dark:text-slate-300">{{ strtoupper((string) $tx->scope) }}</td>
                                    <td class="px-3 py-2 text-right font-semibold {{ $tx->direction === 'in' ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }}">
                                        {{ $tx->direction === 'in' ? '+' : '-' }} R$ {{ number_format((float) $tx->amount, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-5 text-center text-slate-500 dark:text-slate-400">Sem transacoes para o periodo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400">Dados de transacoes ocultos por permissao.</p>
                @endif
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">Igrejas no escopo</h2>
                <dl class="mt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-600 dark:text-slate-300">Total</dt>
                        <dd class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ (int) ($churchStats['total'] ?? 0) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-600 dark:text-slate-300">Ativas</dt>
                        <dd class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">{{ (int) ($churchStats['active'] ?? 0) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-600 dark:text-slate-300">Inadimplentes</dt>
                        <dd class="text-sm font-semibold text-amber-700 dark:text-amber-400">{{ (int) ($churchStats['inadimplente'] ?? 0) }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-600 dark:text-slate-300">Crescimento 12m</dt>
                        <dd class="text-sm font-semibold text-indigo-700 dark:text-indigo-400">{{ (int) ($growthStats['last_12m'] ?? 0) }}</dd>
                    </div>
                </dl>
            </article>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">Top setores por jovens cadastrados</h2>
                <div class="mt-4 space-y-3">
                    @forelse($topSectors as $sector)
                        <div class="rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-700">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $sector['sector_name'] }}</p>
                                <p class="text-sm font-semibold text-indigo-700 dark:text-indigo-400">{{ $sector['youth_count'] }} jovens</p>
                            </div>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Idade media: {{ $sector['average_age'] ?? '-' }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400">Sem dados de censo disponiveis.</p>
                    @endforelse
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">Atas aguardando minha assinatura</h2>
                <div class="mt-4 space-y-3">
                    @forelse($pendingMinutes as $minute)
                        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 dark:border-amber-900/40 dark:bg-amber-950/20">
                            <p class="text-sm font-semibold text-amber-900 dark:text-amber-200">{{ $minute->title }}</p>
                            <p class="mt-1 text-xs text-amber-800/80 dark:text-amber-300/80">
                                {{ optional($minute->updated_at)->format('d/m/Y H:i') }} · {{ $minute->church?->name ?: 'Ata institucional' }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 dark:text-slate-400">Nenhuma ata pendente para aprovacao no momento.</p>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        window.painelDiretoriaDashboardData = @json([
            'sparkline' => $financeKpis['sparkline'] ?? [],
        ]);
    </script>
@endpush
