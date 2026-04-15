@extends('layouts.app')

@section('title', 'Logs de Auditoria')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans" x-data="{
    showCleanModal: false,
    cleanPeriod: '90',
    cleanPeriodLabel: '90 dias'
}">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="clipboard-list" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Logs de Auditoria</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 dark:hover:text-red-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Segurança & Auditoria</span>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 dark:focus:ring-slate-700 transition-colors">
                <x-icon name="arrow-left" class="w-5 h-5" style="duotone" />
                Voltar
            </a>
            <button @click="showCleanModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 transition-all shadow-lg shadow-red-500/20 uppercase tracking-widest text-[10px]">
                <x-icon name="trash" class="w-5 h-5" style="duotone" />
                Limpar Logs
            </button>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Hoje</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-1 italic tracking-tighter">{{ number_format($stats['today'], 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800/50">
                    <x-icon name="calendar" class="w-6 h-6 text-blue-600 dark:text-blue-400" style="duotone" />
                </div>
            </div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Esta Semana</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-1 italic tracking-tighter">{{ number_format($stats['week'], 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800/50">
                    <x-icon name="calendar-days" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" style="duotone" />
                </div>
            </div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Este Mês</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-1 italic tracking-tighter">{{ number_format($stats['month'], 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-100 dark:border-purple-800/50">
                    <x-icon name="calendar-range" class="w-6 h-6 text-purple-600 dark:text-purple-400" style="duotone" />
                </div>
            </div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Geral</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-1 italic tracking-tighter">{{ number_format($stats['total'], 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-100 dark:border-red-800/50">
                    <x-icon name="database" class="w-6 h-6 text-red-600 dark:text-red-400" style="duotone" />
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
            <x-icon name="filter" class="w-4 h-4 text-red-500" style="duotone" />
            <h3 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Filtros Avançados</h3>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.audit.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="space-y-1">
                    <label for="user_id" class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Usuário</label>
                    <select name="user_id" id="user_id" class="block w-full p-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 dark:bg-slate-900 dark:border-slate-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500 font-sans shadow-sm">
                        <option value="">Todos os Usuários</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ ($filters['user_id'] ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label for="module" class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Módulo</label>
                    <select name="module" id="module" class="block w-full p-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 dark:bg-slate-900 dark:border-slate-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500 font-sans shadow-sm">
                        <option value="">Todos os Módulos</option>
                        @foreach($modules as $module)
                            <option value="{{ $module }}" {{ ($filters['module'] ?? '') == $module ? 'selected' : '' }}>{{ ucfirst($module) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-2 space-y-1">
                    <label for="search" class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Pesquisa</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-icon name="magnifying-glass" class="w-4 h-4 text-gray-400" style="duotone" />
                        </div>
                        <input type="text" name="search" id="search" value="{{ $filters['search'] ?? '' }}" class="block w-full pl-10 pr-4 p-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 dark:bg-slate-900 dark:border-slate-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500 font-sans shadow-sm" placeholder="Ação, descrição ou ID...">
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 transition-all uppercase tracking-widest">
                        Filtrar
                    </button>
                    <a href="{{ route('admin.audit.index') }}" class="px-4 py-2.5 text-gray-500 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-xl transition-colors" title="Limpar Filtros">
                        <x-icon name="arrow-path" class="w-5 h-5" style="duotone" />
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Logs -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
            <x-icon name="list" class="w-4 h-4 text-red-500" style="duotone" />
            <h3 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Registros de Atividade</h3>
        </div>

        <div class="overflow-x-auto font-sans">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 font-sans">
                <thead class="text-[10px] font-black text-slate-400 uppercase bg-gray-50/50 dark:bg-slate-900/50 tracking-widest font-sans">
                    <tr>
                        <th scope="col" class="px-6 py-5">Data & Hora</th>
                        <th scope="col" class="px-6 py-5">Responsável</th>
                        <th scope="col" class="px-6 py-5">Ação</th>
                        <th scope="col" class="px-6 py-5">Módulo</th>
                        <th scope="col" class="px-6 py-5">Descrição</th>
                        <th scope="col" class="px-6 py-5 text-right font-sans">Detalhes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 font-sans">
                    @forelse($logs as $log)
                    <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/10 transition-colors group">
                        <td class="px-6 py-5 font-sans">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $log->created_at->format('d/m/Y') }}</span>
                                <span class="text-[10px] font-mono text-gray-400">{{ $log->created_at->format('H:i:s') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 font-sans">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300">
                                    {{ $log->user ? substr($log->user->name, 0, 1) : 'S' }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $log->user->name ?? 'Sistema' }}</span>
                                    <span class="text-[10px] font-mono text-gray-400">{{ $log->ip_address ?? 'Local' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 font-sans">
                            @php
                                $actionColor = match(true) {
                                    Str::contains($log->action, 'create') => 'emerald',
                                    Str::contains($log->action, 'update') => 'blue',
                                    Str::contains($log->action, 'delete') => 'red',
                                    default => 'slate'
                                };
                            @endphp
                            <span class="px-3 py-1 text-[9px] font-black rounded-lg border bg-{{ $actionColor }}-50 text-{{ $actionColor }}-700 border-{{ $actionColor }}-100 dark:bg-{{ $actionColor }}-900/20 dark:text-{{ $actionColor }}-400 dark:border-{{ $actionColor }}-800 uppercase tracking-widest">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-5 font-sans">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide">{{ ucfirst($log->module ?? 'Global') }}</span>
                        </td>
                        <td class="px-6 py-5 font-sans max-w-xs truncate text-gray-600 dark:text-gray-400 font-medium">
                            {{ $log->description }}
                        </td>
                        <td class="px-6 py-5 text-right font-sans">
                            <a href="{{ route('admin.audit.show', $log->id) }}" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-lg hover:bg-red-100 transition-all shadow-sm font-sans" title="Ver Detalhes">
                                <x-icon name="eye" class="w-4 h-4 font-sans" style="duotone" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium italic">Nenhum log encontrado com os critérios atuais.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/30">
            {{ $logs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    <!-- Modal de Limpeza (Alpine.js) -->
    <div x-show="showCleanModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
         style="display: none;">

        <div @click.away="showCleanModal = false"
             class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden border border-gray-200 dark:border-slate-700 transition-all font-sans"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-12 scale-95"
             x-transition:enter-end="translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0 scale-100"
             x-transition:leave-end="translate-y-12 scale-95">

            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600 dark:text-red-400 ring-8 ring-red-50/50 dark:ring-red-900/10">
                    <x-icon name="trash" class="w-10 h-10 animate-bounce" style="duotone" />
                </div>

                <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Limpeza de Auditoria</h2>
                <p class="text-gray-500 dark:text-gray-400 font-medium mb-8">Escolha o período de preservação. Logs anteriores ao período serão removidos.</p>

                <div class="space-y-4 mb-8">
                    <label class="block text-left text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Periodo de Preservação</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach([
                            ['days' => '7', 'label' => '7 dias'],
                            ['days' => '30', 'label' => '30 dias'],
                            ['days' => '90', 'label' => '90 dias'],
                            ['days' => '180', 'label' => '6 meses'],
                            ['days' => '365', 'label' => '1 ano'],
                            ['days' => '0', 'label' => 'Limpar Tudo']
                        ] as $option)
                        <button @click="cleanPeriod = '{{ $option['days'] }}'; cleanPeriodLabel = '{{ $option['label'] }}'"
                                :class="cleanPeriod === '{{ $option['days'] }}' ? 'bg-red-600 text-white shadow-lg shadow-red-200 dark:shadow-none border-red-600' : 'bg-gray-50 dark:bg-slate-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-slate-700'"
                                class="flex items-center justify-center p-4 rounded-xl border-2 font-bold text-sm transition-all hover:scale-[1.03] active:scale-95 uppercase tracking-wide">
                            {{ $option['label'] }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-2xl p-4 border border-amber-100 dark:border-amber-900/30 flex items-start gap-4 mb-8 text-left">
                    <x-icon name="triangle-exclamation" class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-1" style="duotone" />
                    <div>
                        <p class="text-sm font-bold text-amber-800 dark:text-amber-300">Atenção!</p>
                        <p class="text-xs font-medium text-amber-700 dark:text-amber-400">Ação irreversível. <span x-text="cleanPeriod === '0' ? 'TODOS os logs serão excluídos.' : 'Logs anteriores a ' + cleanPeriodLabel + ' serão deletados.'"></span></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button @click="showCleanModal = false" class="px-6 py-4 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-slate-800 dark:border-slate-700 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-xl transition-all uppercase tracking-widest text-xs">
                        Cancelar
                    </button>
                    <form action="{{ route('admin.audit.clean') }}" method="POST">
                        @csrf
                        <input type="hidden" name="days" :value="cleanPeriod">
                        <button type="submit" class="w-full px-6 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-red-200 dark:shadow-none hover:scale-[1.03] active:scale-95 uppercase tracking-widest text-xs">
                            Confirmar Exclusão
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
