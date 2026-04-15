@extends($layout)

@section('title', 'Talentos JUBAF')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('talentos::paineldiretoria.partials.subnav', ['active' => 'dashboard'])

    <div class="relative overflow-hidden rounded-3xl border border-violet-200/60 bg-gradient-to-br from-white via-violet-50/50 to-fuchsia-50/30 shadow-lg shadow-violet-900/5 dark:border-violet-900/30 dark:from-slate-900 dark:via-violet-950/20 dark:to-slate-900">
        <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-violet-400/15 blur-3xl dark:bg-violet-500/10" aria-hidden="true"></div>
        <div class="relative flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-700 dark:text-violet-400">JUBAF · Voluntariado</p>
                <h1 class="mt-2 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-600 text-white shadow-lg shadow-violet-600/30 ring-4 ring-violet-600/10 dark:ring-violet-400/10">
                        <x-module-icon module="Talentos" class="h-7 w-7" />
                    </span>
                    Banco de talentos
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                    <span class="font-medium text-gray-800 dark:text-gray-200">Fluxo:</span> jovens e líderes preenchem a ficha → a diretoria pesquisa no diretório → cria atribuições (com evento do calendário, se aplicável) → o membro responde ao convite no próprio painel. Use o diretório para encontrar pessoas e as atribuições para acompanhar estados.
                </p>
            </div>
            <div class="flex shrink-0 flex-wrap gap-2">
                @can('talentos.directory.view')
                    <a href="{{ route('diretoria.talentos.directory.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white/80 px-4 py-2.5 text-sm font-semibold text-gray-800 backdrop-blur-sm transition hover:border-violet-200 hover:bg-white dark:border-slate-600 dark:bg-slate-800/80 dark:text-white dark:hover:border-violet-700">
                        <x-icon name="users" class="h-4 w-4 text-violet-600 dark:text-violet-400" style="duotone" />
                        Diretório
                    </a>
                @endcan
                @can('create', \Modules\Talentos\App\Models\TalentAssignment::class)
                    <a href="{{ route('diretoria.talentos.assignments.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/25 transition hover:bg-violet-700">
                        <x-icon name="plus" class="h-4 w-4" style="solid" />
                        Nova atribuição
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @if($canDirectory)
            <div class="rounded-2xl border border-violet-100 bg-white p-5 shadow-sm ring-1 ring-violet-500/5 dark:border-violet-900/40 dark:bg-slate-800">
                <p class="text-xs font-bold uppercase tracking-wide text-violet-700/80 dark:text-violet-400/90">Perfis</p>
                <p class="mt-2 text-2xl font-bold tabular-nums text-violet-800 dark:text-violet-200">{{ $profileTotal }}</p>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Com ficha no módulo</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm dark:border-emerald-900/40 dark:bg-slate-800">
                <p class="text-xs font-bold uppercase tracking-wide text-emerald-800/80 dark:text-emerald-400/90">Pesquisáveis</p>
                <p class="mt-2 text-2xl font-bold tabular-nums text-emerald-700 dark:text-emerald-300">{{ $searchableTotal }}</p>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Visíveis no diretório da diretoria</p>
            </div>
        @endif
        @if($canAssignments)
            <div class="rounded-2xl border border-fuchsia-100 bg-white p-5 shadow-sm dark:border-fuchsia-900/40 dark:bg-slate-800 sm:col-span-1">
                <p class="text-xs font-bold uppercase tracking-wide text-fuchsia-800/80 dark:text-fuchsia-400/90">Atribuições</p>
                <p class="mt-2 text-2xl font-bold tabular-nums text-fuchsia-800 dark:text-fuchsia-200">{{ $assignmentTotal }}</p>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Convites e funções registados</p>
            </div>
            @if($assignmentInvitedPending !== null && $assignmentInvitedPending > 0)
                <div class="rounded-2xl border border-amber-200 bg-amber-50/80 p-5 shadow-sm dark:border-amber-900/40 dark:bg-amber-950/30 sm:col-span-1">
                    <p class="text-xs font-bold uppercase tracking-wide text-amber-900/90 dark:text-amber-300/90">Convites por responder</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-amber-900 dark:text-amber-100">{{ $assignmentInvitedPending }}</p>
                    <a href="{{ route('diretoria.talentos.assignments.index', ['status' => 'invited']) }}" class="mt-2 inline-block text-xs font-bold text-amber-800 underline hover:no-underline dark:text-amber-200">Ver pendentes</a>
                </div>
            @endif
        @endif
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        @if($canDirectory)
            <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Fichas recentes</h2>
                    <a href="{{ route('diretoria.talentos.directory.index') }}" class="text-sm font-semibold text-violet-700 hover:underline dark:text-violet-400">Ver tudo</a>
                </div>
                <ul class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($recentProfiles as $p)
                        <li class="flex items-center justify-between gap-3 px-5 py-3">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $p->user?->name ?? '—' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $p->user?->church?->name ?? '—' }}</p>
                            </div>
                            <a href="{{ route('diretoria.talentos.directory.show', $p->user_id) }}" class="shrink-0 text-xs font-bold text-violet-700 hover:underline dark:text-violet-400">Ficha</a>
                        </li>
                    @empty
                        <li class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">Ainda sem perfis.</li>
                    @endforelse
                </ul>
            </div>
        @endif

        @if($canAssignments)
            <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Últimas atribuições</h2>
                    <a href="{{ route('diretoria.talentos.assignments.index') }}" class="text-sm font-semibold text-violet-700 hover:underline dark:text-violet-400">Ver tudo</a>
                </div>
                <ul class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($recentAssignments as $a)
                        <li class="px-5 py-3">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $a->user?->name ?? '—' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $a->role_label }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $a->calendarEvent?->title ?? 'Sem evento' }} · <span class="uppercase">{{ $a->status }}</span></p>
                        </li>
                    @empty
                        <li class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">Sem atribuições.</li>
                    @endforelse
                </ul>
            </div>
        @endif
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Atalhos rápidos</h2>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mesmo fluxo da Secretaria: use o diretório para consultar e as atribuições para convites formais.</p>
        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
            @can('talentos.directory.view')
                <a href="{{ route('diretoria.talentos.directory.index') }}" class="rounded-xl border border-gray-200 p-4 text-center text-sm font-semibold text-gray-900 transition hover:border-violet-300 hover:bg-violet-50/50 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-violet-700">Diretório</a>
            @endcan
            @can('talentos.directory.export')
                <a href="{{ route('diretoria.talentos.directory.export.csv') }}" class="rounded-xl border border-gray-200 p-4 text-center text-sm font-semibold text-gray-900 transition hover:border-violet-300 dark:hover:bg-violet-50/50 dark:border-slate-600 dark:text-white dark:hover:border-violet-700">Exportar CSV</a>
            @endcan
            @can('viewAny', \Modules\Talentos\App\Models\TalentAssignment::class)
                <a href="{{ route('diretoria.talentos.assignments.index') }}" class="rounded-xl border border-gray-200 p-4 text-center text-sm font-semibold text-gray-900 transition hover:border-violet-300 dark:hover:bg-violet-50/50 dark:border-slate-600 dark:text-white dark:hover:border-violet-700">Atribuições</a>
            @endcan
            @can('create', \Modules\Talentos\App\Models\TalentAssignment::class)
                <a href="{{ route('diretoria.talentos.assignments.create') }}" class="rounded-xl border border-violet-200 bg-violet-50/80 p-4 text-center text-sm font-semibold text-violet-900 transition hover:bg-violet-100 dark:border-violet-800 dark:bg-violet-950/40 dark:text-violet-100">Nova atribuição</a>
            @endcan
            @can('talentos.taxonomy.manage')
                @if(Route::has('diretoria.talentos.competencias.index'))
                    <a href="{{ route('diretoria.talentos.competencias.index') }}" class="rounded-xl border border-gray-200 p-4 text-center text-sm font-semibold text-gray-900 transition hover:border-violet-300 dark:border-slate-600 dark:text-white dark:hover:border-violet-700">Competências e áreas</a>
                @endif
            @endcan
        </div>
    </div>
</div>
@endsection
