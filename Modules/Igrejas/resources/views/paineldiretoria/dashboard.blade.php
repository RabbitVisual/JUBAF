@extends($layout)

@section('title', 'Igrejas JUBAF')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('igrejas::paineldiretoria.partials.subnav', ['active' => 'dashboard'])

    <div class="relative overflow-hidden rounded-3xl border border-cyan-200/60 bg-gradient-to-br from-white via-cyan-50/50 to-sky-50/30 shadow-lg shadow-cyan-900/5 dark:border-cyan-900/30 dark:from-slate-900 dark:via-cyan-950/20 dark:to-slate-900">
        <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-cyan-400/15 blur-3xl dark:bg-cyan-500/10" aria-hidden="true"></div>
        <div class="relative flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-cyan-800 dark:text-cyan-400">JUBAF · Cadastro associacional</p>
                <h1 class="mt-2 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-600 text-white shadow-lg shadow-cyan-600/30 ring-4 ring-cyan-600/10 dark:ring-cyan-400/10">
                        <x-module-icon module="Igrejas" class="h-7 w-7" />
                    </span>
                    Congregações ASBAF / JUBAF
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                    Cadastro canónico das igrejas com trabalho de juventude — alinhado à secretaria, ao calendário e aos painéis de líderes e Unijovem. Use a lista para pesquisar, exportar CSV e editar fichas.
                </p>
            </div>
            <div class="flex shrink-0 flex-wrap gap-2">
                <a href="{{ route('diretoria.igrejas.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white/80 px-4 py-2.5 text-sm font-semibold text-gray-800 backdrop-blur-sm transition hover:border-cyan-200 hover:bg-white dark:border-slate-600 dark:bg-slate-800/80 dark:text-white dark:hover:border-cyan-700">
                    <x-icon name="list" class="h-4 w-4 text-cyan-600 dark:text-cyan-400" style="duotone" />
                    Ver lista
                </a>
                @can('create', \Modules\Igrejas\App\Models\Church::class)
                    <a href="{{ route('diretoria.igrejas.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">
                        <x-icon name="plus" class="h-4 w-4" style="solid" />
                        Nova congregação
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    @if($pendingRequestsCount > 0)
        <div class="rounded-2xl border border-amber-200 bg-amber-50/80 p-5 dark:border-amber-900/50 dark:bg-amber-950/30">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-bold text-amber-900 dark:text-amber-200">{{ $pendingRequestsCount }} pedido(s) aguardam análise</p>
                    <p class="text-xs text-amber-800/90 dark:text-amber-300/90">Alterações propostas por líderes locais.</p>
                </div>
                <a href="{{ route('diretoria.igrejas.requests.index', ['status' => 'submitted']) }}" class="rounded-xl bg-amber-600 px-4 py-2 text-sm font-bold text-white hover:bg-amber-700">Ver fila</a>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-cyan-100 bg-white p-5 shadow-sm ring-1 ring-cyan-500/5 dark:border-cyan-900/40 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase tracking-wide text-cyan-800/80 dark:text-cyan-400/90">Total</p>
            <p class="mt-2 text-2xl font-bold tabular-nums text-cyan-900 dark:text-cyan-200">{{ $totalChurches }}</p>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Congregações no cadastro</p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm dark:border-emerald-900/40 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase tracking-wide text-emerald-800/80 dark:text-emerald-400/90">Ativas</p>
            <p class="mt-2 text-2xl font-bold tabular-nums text-emerald-700 dark:text-emerald-300">{{ $activeChurches }}</p>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Visíveis nos fluxos operacionais</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-600 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-600 dark:text-slate-400">Inativas</p>
            <p class="mt-2 text-2xl font-bold tabular-nums text-slate-800 dark:text-slate-200">{{ $inactiveChurches }}</p>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">A regularizar ou arquivo</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        @if($churchesMissingLeadership->isNotEmpty())
            <div class="overflow-hidden rounded-2xl border border-amber-200/90 bg-white shadow-sm dark:border-amber-900/40 dark:bg-slate-800 lg:col-span-2">
                <div class="border-b border-amber-100 px-5 py-4 dark:border-amber-900/40">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Congregações sem pastor ou líder Unijovem nomeado</h2>
                </div>
                <ul class="divide-y divide-gray-100 dark:divide-slate-700">
                    @foreach($churchesMissingLeadership as $c)
                        <li class="flex items-center justify-between gap-3 px-5 py-3">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $c->name }}</span>
                            <a href="{{ route('diretoria.igrejas.edit', $c) }}" class="text-xs font-bold text-cyan-700 hover:underline dark:text-cyan-400">Completar ficha</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($upcomingAnniversaries->isNotEmpty())
            <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800 lg:col-span-2">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Datas de fundação (referência)</h2>
                </div>
                <ul class="divide-y divide-gray-100 dark:divide-slate-700">
                    @foreach($upcomingAnniversaries as $c)
                        <li class="flex items-center justify-between gap-3 px-5 py-3 text-sm">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $c->name }}</span>
                            <span class="text-gray-500">{{ $c->foundation_date?->format('d/m') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Atualizadas recentemente</h2>
                <a href="{{ route('diretoria.igrejas.index') }}" class="text-sm font-semibold text-cyan-700 hover:underline dark:text-cyan-400">Lista completa</a>
            </div>
            <ul class="divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($recentChurches as $c)
                    <li class="flex items-center justify-between gap-3 px-5 py-3">
                        <div class="min-w-0">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $c->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $c->city ?? '—' }} · {{ $c->jovens_members_count }} jovens · {{ $c->leaders_count }} líderes</p>
                        </div>
                        <a href="{{ route('diretoria.igrejas.show', $c) }}" class="shrink-0 text-xs font-bold text-cyan-700 hover:underline dark:text-cyan-400">Ficha</a>
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">Ainda sem congregações registadas.</li>
                @endforelse
            </ul>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Maior presença Unijovem</h2>
                <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Top 5</span>
            </div>
            <ul class="divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($topJovensChurches as $c)
                    <li class="flex items-center justify-between gap-3 px-5 py-3">
                        <div class="min-w-0">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $c->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $c->city ?? '—' }}</p>
                        </div>
                        <span class="shrink-0 rounded-lg bg-cyan-50 px-2.5 py-1 text-sm font-bold tabular-nums text-cyan-900 dark:bg-cyan-950/50 dark:text-cyan-200">{{ $c->jovens_members_count }}</span>
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">Sem dados de jovens por igreja.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Atalhos rápidos</h2>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mesmo fluxo da secretaria: cadastro central aqui; pastores e líderes veem dados no âmbito da congregação.</p>
        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
            <a href="{{ route('diretoria.igrejas.index') }}" class="rounded-xl border border-gray-200 p-4 text-center text-sm font-semibold text-gray-900 transition hover:border-cyan-300 hover:bg-cyan-50/50 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-cyan-700">Lista</a>
            @can('export', \Modules\Igrejas\App\Models\Church::class)
                <a href="{{ route('diretoria.igrejas.export.csv') }}" class="rounded-xl border border-gray-200 p-4 text-center text-sm font-semibold text-gray-900 transition hover:border-cyan-300 dark:hover:bg-cyan-50/50 dark:border-slate-600 dark:text-white dark:hover:border-cyan-700">Exportar CSV</a>
            @endcan
            @if(Route::has('igrejas.public.index'))
                <a href="{{ route('igrejas.public.index') }}" target="_blank" rel="noopener noreferrer" class="rounded-xl border border-gray-200 p-4 text-center text-sm font-semibold text-gray-900 transition hover:border-sky-300 hover:bg-sky-50/50 dark:border-slate-600 dark:text-white dark:hover:border-sky-700">Página pública</a>
            @endif
            @can('create', \Modules\Igrejas\App\Models\Church::class)
                <a href="{{ route('diretoria.igrejas.create') }}" class="rounded-xl border border-cyan-200 bg-cyan-50/80 p-4 text-center text-sm font-semibold text-cyan-900 transition hover:bg-cyan-100 dark:border-cyan-800 dark:bg-cyan-950/40 dark:text-cyan-100">Nova congregação</a>
            @endcan
            @can('igrejas.requests.review')
                <a href="{{ route('diretoria.igrejas.requests.index') }}" class="rounded-xl border border-amber-200 bg-amber-50/80 p-4 text-center text-sm font-semibold text-amber-900 transition hover:bg-amber-100 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-100">Pedidos</a>
            @endcan
        </div>
    </div>
</div>
@endsection
