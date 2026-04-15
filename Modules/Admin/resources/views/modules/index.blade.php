@extends($layout)

@section('title', 'Gerenciamento de Módulos')

@section('content')
@php
    $dashRoute = user_can_access_admin_panel() ? 'admin.dashboard' : 'diretoria.dashboard';
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
    <header class="overflow-hidden rounded-3xl border border-indigo-100/90 bg-gradient-to-br from-indigo-50/90 via-white to-white p-6 shadow-sm dark:border-indigo-900/25 dark:from-indigo-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Sistema · Extensões</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Módulos instalados</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Active ou desactive add-ons, filtre por estado e abra a ficha técnica de cada módulo. Alterações aplicam-se nas próximas requisições.
                </p>
                <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500" aria-label="breadcrumb">
                    <a href="{{ route($dashRoute) }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Admin</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                    <span class="font-medium text-gray-800 dark:text-slate-300">Extensões e add-ons</span>
                </nav>
            </div>
        </div>
    </header>

    <div class="flex gap-4 rounded-2xl border border-sky-200/80 bg-sky-50/90 p-4 dark:border-sky-900/40 dark:bg-sky-950/30 md:items-center md:p-5">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-200/80 text-sky-800 dark:bg-sky-900/60 dark:text-sky-200">
            <x-icon name="circle-info" class="h-5 w-5" style="duotone" />
        </span>
        <p class="min-w-0 text-sm leading-relaxed text-sky-950/90 dark:text-sky-100/90">
            <span class="font-semibold text-sky-900 dark:text-sky-100">Desactivar um módulo</span>
            — interrompe rotas e serviços associados. Confirme dependências antes de desligar em produção.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-indigo-500/10 blur-2xl"></div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Total</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $overallStats['total'] ?? 0 }}</p>
            <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Módulos no projecto</p>
        </div>
        <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-emerald-500/10 blur-2xl"></div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Activos</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $overallStats['enabled'] ?? 0 }}</p>
            <p class="mt-2 flex items-center gap-1 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                <x-icon name="check-circle" class="h-3 w-3" style="solid" />
                Operacionais
            </p>
        </div>
        <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:col-span-2 lg:col-span-1">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-slate-500/10 blur-2xl"></div>
            <p class="text-[11px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Desactivados</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $overallStats['disabled'] ?? 0 }}</p>
            <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">Inativos neste ambiente</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
        <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Filtros</h2>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Pesquise por nome ou restrinja por estado</p>
        </div>
        <div class="p-6 md:p-8">
            <form action="{{ route($routePrefix.'.index') }}" method="GET" class="flex flex-col items-end gap-6 md:flex-row">
                <div class="group relative w-full flex-1">
                    <label class="mb-2 block px-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Buscar módulo</label>
                    <div class="pointer-events-none absolute inset-y-0 left-0 top-8 flex items-center pl-4">
                        <x-icon name="magnifying-glass" class="h-5 w-5 text-slate-400 transition-colors group-focus-within:text-indigo-500" />
                    </div>
                    <input type="text" name="search" value="{{ $search }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-12 pr-5 text-sm transition-all placeholder:text-slate-400 focus:border-transparent focus:ring-2 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                        placeholder="Nome, descrição ou alias…">
                </div>

                <div class="w-full md:w-64">
                    <label class="mb-2 block px-1 text-xs font-semibold uppercase tracking-wider text-slate-500">Estado</label>
                    <select name="filter"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm transition-all focus:ring-2 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Todos os módulos</option>
                        <option value="enabled" {{ $filter === 'enabled' ? 'selected' : '' }}>Apenas habilitados</option>
                        <option value="disabled" {{ $filter === 'disabled' ? 'selected' : '' }}>Apenas desabilitados</option>
                    </select>
                </div>

                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-8 py-3 text-sm font-medium text-white shadow-sm transition-all hover:bg-slate-800 active:scale-95 dark:bg-slate-700 dark:hover:bg-slate-600 md:w-auto">
                    <x-icon name="sliders" style="duotone" class="h-5 w-5" />
                    Filtrar
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($modules as $module)
            <div class="group relative flex h-full flex-col overflow-hidden rounded-3xl border border-gray-200/90 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-slate-700 dark:bg-slate-800/80">
                <div class="absolute left-0 right-0 top-0 h-1.5 {{ $module['enabled'] ? 'bg-gradient-to-r from-emerald-400 to-teal-500' : 'bg-slate-200 dark:bg-slate-700' }}"></div>

                <div class="mb-4 mt-2 flex items-start justify-between">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $module['enabled'] ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'bg-slate-100 text-slate-400 dark:bg-slate-700' }}">
                        <x-icon name="cube" style="duotone" class="h-6 w-6" />
                    </div>

                    <div class="flex flex-col items-end">
                        @if ($module['enabled'])
                            <span class="mb-1 inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"></span>
                                ATIVO
                            </span>
                        @else
                            <span class="mb-1 inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600 dark:bg-slate-700 dark:text-slate-400">
                                INATIVO
                            </span>
                        @endif
                        <span class="font-mono text-[10px] text-slate-400">v{{ $module['version'] ?? '1.0.0' }}</span>
                    </div>
                </div>

                <h3 class="mb-2 text-lg font-bold text-gray-900 transition-colors group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-400">
                    {{ $module['name'] }}
                </h3>

                <p class="mb-6 line-clamp-3 flex-grow text-sm leading-relaxed text-slate-500">
                    {{ $module['description'] }}
                </p>

                <div class="flex items-center justify-between border-t border-gray-100 pt-4 dark:border-slate-700">
                    <a href="{{ route($routePrefix.'.show', $module['name']) }}" class="flex items-center gap-1 text-sm font-semibold text-indigo-600 transition-colors hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                        Gerenciar
                        <x-icon name="arrow-right" class="h-4 w-4" />
                    </a>

                    @if ($module['enabled'])
                        <form action="{{ route($routePrefix.'.disable', $module['name']) }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded-lg p-2 text-slate-400 transition-all hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-900/20" title="Desabilitar módulo">
                                <x-icon name="power-off" class="h-5 w-5" />
                            </button>
                        </form>
                    @else
                        <form action="{{ route($routePrefix.'.enable', $module['name']) }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded-lg p-2 text-slate-400 transition-all hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/20" title="Habilitar módulo">
                                <x-icon name="play" class="h-5 w-5" />
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <span class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-slate-300 dark:bg-slate-800">
                    <x-icon name="cubes" style="duotone" class="h-10 w-10" />
                </span>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Nenhum módulo encontrado</h3>
                <p class="mt-1 text-sm text-slate-500">Tente ajustar os filtros de busca.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
