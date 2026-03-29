{{-- Inteligência executiva: KPIs evento, ranking igrejas, fundos tesouraria --}}
@if($showExecutiveWarRoom && $hasExecutiveModules)
<div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950 p-6 text-white shadow-xl dark:border-slate-700 md:p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-indigo-300">War room</p>
            <h2 class="text-2xl font-black tracking-tight md:text-3xl">Inteligência executiva</h2>
            <p class="mt-1 max-w-xl text-sm text-slate-300">Visão consolidada para assembleias e decisão da diretoria — otimizada para telemóvel.</p>
        </div>
        @if(Route::has('admin.diretoria.minutes.index') && auth()->user()->canAccessAny(['governance_manage', 'governance_view']))
            <a href="{{ route('admin.diretoria.minutes.index') }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold backdrop-blur hover:bg-white/20">
                <x-icon name="file-pdf" class="h-4 w-4" />
                Atas PDF (admin)
            </a>
        @endif
        @if(Route::has('memberpanel.governance.diretoria.minutes.index') && auth()->user()->canAccessAny(['governance_manage', 'governance_view']))
            <a href="{{ route('memberpanel.governance.diretoria.minutes.index') }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold backdrop-blur hover:bg-white/20">
                <x-icon name="file-pdf" class="h-4 w-4" />
                Atas (painel)
            </a>
        @endif
    </div>

    @if(!empty($executiveEventKpis))
        <div class="mb-8">
            <h3 class="mb-3 flex items-center gap-2 text-sm font-bold text-slate-200">
                <x-icon name="chart-bar" class="h-4 w-4 text-emerald-400" />
                Arrecadação vs. despesas previstas (por evento)
            </h3>
            <div class="-mx-1 overflow-x-auto pb-2">
                <div class="flex min-w-[min(100%,640px)] flex-col gap-3 sm:min-w-0">
                    @foreach($executiveEventKpis as $row)
                        @php
                            $balance = $row['collected'] - $row['planned_expenses'];
                        @endphp
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-bold text-white">{{ $row['title'] }}</p>
                                    @if($row['start_date'])
                                        <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($row['start_date'])->translatedFormat('d M Y') }} · {{ $row['registrations'] }} inscrições</p>
                                    @endif
                                </div>
                                <span class="shrink-0 rounded-lg px-2 py-1 text-xs font-bold {{ $balance >= 0 ? 'bg-emerald-500/20 text-emerald-200' : 'bg-amber-500/20 text-amber-100' }}">
                                    Saldo: R$ {{ number_format($balance, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="mt-3 grid grid-cols-2 gap-3 text-sm sm:grid-cols-3">
                                <div>
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Arrecadado</p>
                                    <p class="font-mono font-bold text-emerald-300">R$ {{ number_format($row['collected'], 2, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Desp. previstas</p>
                                    <p class="font-mono font-bold text-rose-300">R$ {{ number_format($row['planned_expenses'], 2, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        @if($executiveChurchRanking->isNotEmpty())
            <div class="rounded-2xl border border-white/10 bg-black/20 p-4 md:p-5">
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold text-slate-200">
                    <x-icon name="map-location-dot" class="h-4 w-4 text-sky-400" />
                    Ranking de engajamento (inscrições por igreja)
                </h3>
                <p class="mb-3 text-xs text-slate-500">Eventos publicados atuais ou futuros · atualizado em cache (5 min).</p>
                <div class="h-72 w-full min-h-[16rem]">
                    <canvas id="executiveEngagementChart" class="max-h-72"></canvas>
                </div>
            </div>
        @endif

        @if($executiveFundSplit)
            <div class="rounded-2xl border border-white/10 bg-black/20 p-4 md:p-5 {{ $executiveChurchRanking->isEmpty() ? 'lg:col-span-2' : '' }}">
                <h3 class="mb-4 flex items-center gap-2 text-sm font-bold text-slate-200">
                    <x-icon name="wallet" class="h-4 w-4 text-violet-400" />
                    Tesouraria — saldo por fundo
                </h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-violet-500/30 bg-violet-500/10 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-violet-200">{{ $executiveFundSplit['reserve']['label'] }}</p>
                        <p class="mt-2 font-mono text-2xl font-black text-white">R$ {{ number_format($executiveFundSplit['reserve']['net'], 2, ',', '.') }}</p>
                        <p class="mt-1 text-[11px] text-slate-400">Líquido (entradas − saídas) no centro de custo.</p>
                    </div>
                    <div class="rounded-xl border border-teal-500/30 bg-teal-500/10 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-teal-200">{{ $executiveFundSplit['events_fund']['label'] }}</p>
                        <p class="mt-2 font-mono text-2xl font-black text-white">R$ {{ number_format($executiveFundSplit['events_fund']['net'], 2, ',', '.') }}</p>
                        <p class="mt-1 text-[11px] text-slate-400">Alocação semântica para eventos federais.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endif
