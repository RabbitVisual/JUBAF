@extends('paineldiretoria::components.layouts.app')

@section('title', 'Gerenciamento de Módulos')

@section('content')
<div class="mx-auto max-w-7xl space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    @include('paineldiretoria::partials.modules-subnav', ['active' => 'lista'])

    <div class="relative overflow-hidden rounded-3xl border border-violet-200/60 bg-gradient-to-br from-violet-50/90 via-white to-indigo-50/50 p-6 shadow-md dark:border-violet-900/30 dark:from-violet-950/25 dark:via-slate-900 dark:to-slate-900 sm:p-8">
        <div class="pointer-events-none absolute -left-10 top-0 h-40 w-40 rounded-full bg-violet-400/15 blur-3xl dark:bg-violet-500/10" aria-hidden="true"></div>
        <div class="relative">
            <nav aria-label="breadcrumb" class="mb-3 flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                <a href="{{ route('diretoria.dashboard') }}" class="font-medium text-violet-700 hover:underline dark:text-violet-400">Painel da diretoria</a>
                <x-icon name="chevron-right" class="h-3 w-3 shrink-0 text-slate-400" style="duotone" />
                <span class="font-semibold text-gray-900 dark:text-white">Módulos Laravel</span>
            </nav>
            <h1 class="flex flex-wrap items-center gap-3 text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 text-white shadow-lg shadow-violet-600/25">
                    <x-icon name="cubes-stacked" class="h-7 w-7" style="duotone" />
                </span>
                Extensões do sistema
            </h1>
            <p class="mt-2 max-w-3xl text-sm text-gray-600 dark:text-gray-400">
                Ative ou desative módulos por região. Cada cartão abre o detalhe com dependências, permissões e estado em tempo real.
            </p>
        </div>
    </div>

    <!-- Estatísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 dark:bg-indigo-900/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider mb-2">Total de Módulos</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $overallStats['total'] ?? 0 }}</span>
                    <span class="text-xs text-slate-500">instalados</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider mb-2">Módulos Ativos</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $overallStats['enabled'] ?? 0 }}</span>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1">
                        <x-icon name="check-circle" class="w-3 h-3" style="solid" />
                        Operacionais
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Desabilitados</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $overallStats['disabled'] ?? 0 }}</span>
                    <span class="text-xs text-slate-500">inativos</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros e Busca -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 md:p-8">
        <form action="{{ route('diretoria.modules.index') }}" method="GET" class="flex flex-col md:flex-row gap-6 items-end">
            <div class="relative flex-1 group">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 px-1">Buscar Módulo</label>
                <div class="absolute inset-y-0 left-0 pl-4 top-8 flex items-center pointer-events-none">
                    <x-icon name="magnifying-glass" class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                </div>
                <input type="text" name="search" value="{{ $search }}"
                    class="w-full pl-12 pr-5 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400"
                    placeholder="Nome, descrição ou alias...">
            </div>

            <div class="w-full md:w-64">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 px-1">Estado</label>
                <select name="filter"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all dark:text-white">
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Todos os Módulos</option>
                    <option value="enabled" {{ $filter === 'enabled' ? 'selected' : '' }}>Apenas Habilitados</option>
                    <option value="disabled" {{ $filter === 'disabled' ? 'selected' : '' }}>Apenas Desabilitados</option>
                </select>
            </div>

            <button type="submit" class="w-full md:w-auto px-8 py-3 text-sm font-medium text-white bg-slate-900 dark:bg-slate-700 rounded-xl hover:bg-slate-800 dark:hover:bg-slate-600 transition-all shadow-sm active:scale-95 flex items-center justify-center gap-2">
                <x-icon name="sliders" style="duotone" class="w-5 h-5" />
                Filtrar
            </button>
        </form>
    </div>

    <!-- Grid de Módulos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($modules as $module)
            <div class="group bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-all flex flex-col h-full relative overflow-hidden">
                <!-- Status Stripe -->
                <div class="absolute top-0 left-0 right-0 h-1.5 {{ $module['enabled'] ? 'bg-gradient-to-r from-emerald-400 to-teal-500' : 'bg-slate-200 dark:bg-slate-700' }}"></div>

                <div class="flex items-start justify-between mb-4 mt-2">
                    <div class="w-12 h-12 rounded-2xl {{ $module['enabled'] ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : 'bg-slate-100 dark:bg-slate-700 text-slate-400' }} flex items-center justify-center">
                        <x-icon name="cube" style="duotone" class="w-6 h-6" />
                    </div>

                    <div class="flex flex-col items-end">
                         @if($module['enabled'])
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 mb-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                ATIVO
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400 mb-1">
                                INATIVO
                            </span>
                        @endif
                        <span class="text-[10px] font-mono text-slate-400">v{{ $module['version'] ?? '1.0.0' }}</span>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                    {{ $module['name'] }}
                </h3>

                <p class="text-sm text-slate-500 flex-grow leading-relaxed mb-6 line-clamp-3">
                    {{ $module['description'] }}
                </p>

                <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-between">
                    <a href="{{ route('diretoria.modules.show', $module['name']) }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors flex items-center gap-1">
                        Gerenciar
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </a>

                    @if($module['enabled'])
                        <form action="{{ route('diretoria.modules.disable', $module['name']) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all" title="Desabilitar Módulo">
                                <x-icon name="power-off" class="w-5 h-5" />
                            </button>
                        </form>
                    @else
                        <form action="{{ route('diretoria.modules.enable', $module['name']) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all" title="Habilitar Módulo">
                                <x-icon name="play" class="w-5 h-5" />
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center">
                 <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4">
                    <x-icon name="cubes" style="duotone" class="w-10 h-10" />
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Nenhum módulo encontrado</h3>
                <p class="text-sm text-slate-500 mt-1">Tente ajustar os filtros de busca.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
