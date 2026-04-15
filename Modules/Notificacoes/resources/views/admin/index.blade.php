@extends('layouts.app')

@section('title', 'Notificações')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon module="notificacoes" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Notificações</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Gestão de Notificações</span>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 dark:focus:ring-slate-700 transition-colors">
                <x-icon name="arrow-left" class="w-5 h-5" style="duotone" />
                Voltar
            </a>
            <a href="{{ route('admin.notificacoes.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-800 transition-all shadow-lg shadow-emerald-500/20 uppercase tracking-widest text-[10px]">
                <x-icon name="plus" class="w-5 h-5" style="duotone" />
                Nova Notificação
            </a>
            @if(count($notifications) > 0)
                <form action="{{ route('admin.notificacoes.markAllAsRead') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest text-[10px]">
                        <x-icon name="check-double" class="w-5 h-5" style="duotone" />
                        Marcar Tudo como Lido
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Estatísticas (Simuladas por enquanto, já que o controller pode não passar) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-1 italic tracking-tighter">{{ $notifications->total() }}</p>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800/50">
                    <x-icon name="bell" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" style="duotone" />
                </div>
            </div>
        </div>
        <!-- Como não temos as variáveis estatísticas, vamos omitir os outros cards ou usar placeholders se necessário.
             Para manter o padrão visual, deixarei apenas o Total por enquanto ou tentarei inferir se possível.
             Mas para garantir que não quebre, vou usar apenas o total disponível no paginator. -->
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
            <x-icon name="filter" class="w-4 h-4 text-indigo-500" style="duotone" />
            <h3 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Filtros Avançados</h3>
        </div>
        <form method="GET" action="{{ route('admin.notificacoes.index') }}" class="p-6 font-sans">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 font-sans">
                <div>
                    <label for="search" class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Pesquisar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <x-icon name="magnifying-glass" class="w-4 h-4 text-gray-400 font-sans" />
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Título ou mensagem..." class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans">
                    </div>
                </div>
                <div>
                    <label for="type" class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic font-sans">Tipo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <x-icon name="tag" class="w-4 h-4 text-gray-400 font-sans" />
                        </div>
                        <select name="type" id="type" class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans appearance-none">
                            <option value="">Todos os Tipos</option>
                            <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Informação</option>
                            <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Sucesso</option>
                            <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Aviso</option>
                            <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>Erro</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="w-full px-5 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest text-[10px]">
                        <div class="flex items-center justify-center gap-2">
                            <x-icon name="magnifying-glass" class="w-5 h-5" style="duotone" />
                            <span>Filtrar</span>
                        </div>
                    </button>
                    <a href="{{ route('admin.notificacoes.index') }}" class="px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-700 transition-colors" title="Limpar Filtros">
                        <x-icon name="rotate-right" class="w-5 h-5" style="duotone" />
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[10px] text-gray-400 uppercase tracking-widest bg-gray-50/50 dark:bg-slate-900/50 font-sans">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-black italic">Notificação</th>
                        <th scope="col" class="px-6 py-4 font-black italic">Tipo</th>
                        <th scope="col" class="px-6 py-4 font-black italic">Módulo</th>
                        <th scope="col" class="px-6 py-4 font-black italic">Data</th>
                        <th scope="col" class="px-6 py-4 font-black italic text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                    @forelse($notifications as $notification)
                    <tr class="group hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors {{ (! $notification->is_read && ! $notification->read_at) ? 'bg-indigo-50/30 dark:bg-indigo-900/10' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-gray-900 dark:text-white {{ (! $notification->is_read && ! $notification->read_at) ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                        {{ $notification->data['title'] ?? ($notification->title ?? 'Sem Título') }}
                                    </span>
                                    @if(! $notification->is_read && ! $notification->read_at)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 uppercase tracking-wide">
                                            Nova
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xl truncate">
                                    {{ $notification->data['message'] ?? ($notification->message ?? '') }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $type = $notification->data['type'] ?? ($notification->type ?? 'info');
                                $colors = [
                                    'info' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                    'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                                    'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                                    'error' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                ];
                                $colorClass = $colors[$type] ?? $colors['info'];
                                $iconMap = [
                                    'info' => 'circle-info',
                                    'success' => 'circle-check',
                                    'warning' => 'triangle-exclamation',
                                    'error' => 'circle-xmark',
                                ];
                                $icon = $iconMap[$type] ?? 'circle-info';
                            @endphp
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide {{ $colorClass }}">
                                <x-icon name="{{ $icon }}" class="w-3.5 h-3.5" style="duotone" />
                                {{ ucfirst($type) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500">
                                    <x-icon name="layer-group" class="w-4 h-4" style="duotone" />
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    @php
                                        $ms = $notification->module_source ?? ($notification->data['module'] ?? null);
                                        $label = $ms ? (config('notificacoes.module_sources')[$ms] ?? $ms) : 'Geral';
                                    @endphp
                                    {{ $label }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                <x-icon name="calendar" class="w-4 h-4" style="duotone" />
                                <span class="text-xs font-medium">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(! $notification->is_read && ! $notification->read_at)
                                <form action="{{ route('admin.notificacoes.markAsRead', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/30 rounded-lg transition-colors" title="Marcar como lida">
                                        <x-icon name="check" class="w-5 h-5" style="duotone" />
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.notificacoes.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta notificação?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Excluir">
                                        <x-icon name="trash" class="w-5 h-5" style="duotone" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="w-20 h-20 bg-gray-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-icon name="bell-slash" class="w-10 h-10 text-gray-300 dark:text-gray-600" style="duotone" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Nenhuma notificação</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Você não possui notificações no momento.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
