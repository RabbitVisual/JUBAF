@extends('layouts.app')

@section('title', 'Chat - Sessões')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon module="chat" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Gestão de Atendimentos</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Chat</span>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.chat.config') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-slate-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 dark:focus:ring-slate-700 transition-colors uppercase tracking-widest text-[10px]">
                <x-icon name="gear" class="w-5 h-5" style="duotone" />
                Configurações
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6">
        <!-- Total Sessions -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total de Sessões</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-1 italic tracking-tighter">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800/50 group-hover:scale-110 transition-transform">
                    <x-icon name="message-dots" class="w-6 h-6 text-blue-600 dark:text-blue-400" style="duotone" />
                </div>
            </div>
        </div>

        <!-- Waiting Sessions -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Aguardando</p>
                    <p class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1 italic tracking-tighter">{{ $stats['waiting'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-100 dark:border-amber-800/50 group-hover:scale-110 transition-transform">
                    <x-icon name="clock" class="w-6 h-6 text-amber-600 dark:text-amber-400" style="duotone" />
                </div>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Em Atendimento</p>
                    <p class="text-2xl font-black text-green-600 dark:text-green-400 mt-1 italic tracking-tighter">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-100 dark:border-green-800/50 group-hover:scale-110 transition-transform">
                    <x-icon name="comments" class="w-6 h-6 text-green-600 dark:text-green-400" style="duotone" />
                </div>
            </div>
        </div>

        <!-- Closed Sessions -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Encerrados</p>
                    <p class="text-2xl font-black text-slate-600 dark:text-slate-400 mt-1 italic tracking-tighter">{{ $stats['closed'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-slate-50 dark:bg-slate-900/20 rounded-xl border border-slate-100 dark:border-slate-800/50 group-hover:scale-110 transition-transform">
                    <x-icon name="check-double" class="w-6 h-6 text-slate-500 dark:text-slate-400" style="duotone" />
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
            <x-icon name="filter" class="w-4 h-4 text-blue-500" style="duotone" />
            <h3 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Filtros de Inteligência</h3>
        </div>
        <form action="{{ route('admin.chat.index') }}" method="GET" class="p-6 font-sans">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 font-sans">
                <div>
                    <label for="search" class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Buscar Visitante</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <x-icon name="magnifying-glass" class="w-4 h-4 text-gray-400 font-sans" />
                        </div>
                        <input type="text" name="search" id="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nome, e-mail, CPF..." class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 transition-all font-sans">
                    </div>
                </div>

                <div>
                    <label for="status" class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Status</label>
                    <select name="status" id="status" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-slate-900 dark:border-slate-700 dark:text-white font-sans">
                        <option value="">Todos os status</option>
                        <option value="waiting" {{ ($filters['status'] ?? '') == 'waiting' ? 'selected' : '' }}>Aguardando</option>
                        <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Ativo</option>
                        <option value="closed" {{ ($filters['status'] ?? '') == 'closed' ? 'selected' : '' }}>Encerrado</option>
                    </select>
                </div>

                <div>
                    <label for="assigned_to" class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Atendente</label>
                    <select name="assigned_to" id="assigned_to" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-slate-900 dark:border-slate-700 dark:text-white font-sans">
                        <option value="">Todos atendentes</option>
                        @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ ($filters['assigned_to'] ?? '') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2 font-sans">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 text-[10px] font-black text-white bg-slate-900 dark:bg-blue-600 rounded-xl hover:bg-slate-800 dark:hover:bg-blue-700 transition-all uppercase tracking-widest shadow-lg shadow-blue-500/10 font-sans">
                        <x-icon name="filter" class="w-4 h-4 font-sans" style="duotone" />
                        Filtrar
                    </button>
                    <a href="{{ route('admin.chat.index') }}" class="inline-flex items-center justify-center p-3 text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors dark:bg-slate-700 dark:text-slate-300">
                        <x-icon name="rotate-right" class="w-5 h-5 font-sans" style="duotone" />
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Sessions Table -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
        @if($sessions->count() > 0)
        <div class="overflow-x-auto font-sans">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 font-sans">
                <thead class="text-[10px] font-black text-slate-400 uppercase bg-gray-50/50 dark:bg-slate-900/50 tracking-widest font-sans">
                    <tr>
                        <th scope="col" class="px-6 py-5">Visitante / Identificação</th>
                        <th scope="col" class="px-6 py-5 text-center">Tipo</th>
                        <th scope="col" class="px-6 py-5 text-center">Status</th>
                        <th scope="col" class="px-6 py-5">Atendente</th>
                        <th scope="col" class="px-6 py-5">Última Atividade</th>
                        <th scope="col" class="px-6 py-5 text-right font-sans">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 font-sans">
                    @foreach($sessions as $session)
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors group">
                        <td class="px-6 py-5 font-sans">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $session->type === 'public' ? 'from-blue-500 to-blue-700' : 'from-purple-500 to-purple-700' }} flex items-center justify-center text-white font-black text-sm shadow-sm border border-gray-100 dark:border-slate-800 group-hover:scale-110 transition-transform">
                                        {{ strtoupper(substr($session->visitor_name ?? 'V', 0, 1)) }}
                                    </div>
                                    @if($session->unread_count_user > 0)
                                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-[9px] font-black text-white border-2 border-white dark:border-slate-800 animate-bounce">
                                        {{ $session->unread_count_user }}
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight group-hover:text-blue-600 transition-colors font-sans">
                                        {{ $session->visitor_name ?? 'Anônimo' }}
                                    </div>
                                    <div class="flex flex-col text-[10px] font-mono font-bold text-gray-400 uppercase tracking-widest mt-1">
                                        <span>{{ $session->visitor_email ?? $session->session_id }}</span>
                                        @if($session->visitor_cpf)
                                        <span class="mt-0.5">CPF: {{ \Modules\Chat\App\Helpers\CpfHelper::format($session->visitor_cpf) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center font-sans">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $session->type === 'public' ? 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800' : 'bg-purple-50 text-purple-600 border-purple-100 dark:bg-purple-900/20 dark:text-purple-400 dark:border-purple-800' }}">
                                {{ $session->type === 'public' ? 'Público' : 'Interno' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center font-sans">
                            @php
                                $statusConfig = [
                                    'waiting' => ['bg' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800', 'label' => 'Aguardando', 'icon' => 'clock'],
                                    'active' => ['bg' => 'bg-green-50 text-green-600 border-green-100 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800', 'label' => 'Ativo', 'icon' => 'message-dots'],
                                    'closed' => ['bg' => 'bg-slate-50 text-slate-600 border-slate-100 dark:bg-slate-900/20 dark:text-slate-400 dark:border-slate-800', 'label' => 'Encerrado', 'icon' => 'check-double'],
                                ];
                                $s = $statusConfig[$session->status] ?? ['bg' => 'bg-slate-50 text-slate-600 border-slate-100', 'label' => $session->status, 'icon' => 'circle-question'];
                            @endphp
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg {{ $s['bg'] }} text-[9px] font-black uppercase tracking-widest border shadow-sm">
                                <x-icon name="{{ $s['icon'] }}" class="w-3 h-3" style="duotone" />
                                {{ $s['label'] }}
                            </div>
                        </td>
                        <td class="px-6 py-5 font-sans">
                            @if($session->assignedTo)
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase">
                                    {{ substr($session->assignedTo->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase underline decoration-2 decoration-blue-500/20 underline-offset-4">{{ $session->assignedTo->name }}</span>
                            </div>
                            @else
                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest border-b border-dashed border-slate-200">Não atribuído</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 font-sans">
                            <div class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                {{ $session->last_activity_at ? $session->last_activity_at->diffForHumans() : ($session->lastMessage?->created_at?->diffForHumans() ?? $session->created_at->diffForHumans()) }}
                            </div>
                            <div class="text-[9px] text-slate-400 font-bold uppercase mt-1">
                                {{ $session->created_at->format('d/m/Y - H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right font-sans">
                            <a href="{{ route('admin.chat.show', $session->id) }}" class="inline-flex items-center justify-center w-10 h-10 text-blue-600 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl hover:bg-blue-100 transition-all shadow-sm font-sans opacity-80 group-hover:opacity-100" title="Visualizar">
                                <x-icon name="eye" class="w-5 h-5 font-sans" style="duotone" />
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($sessions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/30">
            {{ $sessions->links() }}
        </div>
        @endif
        @else
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-900/50 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm border border-slate-100 dark:border-slate-700">
                <x-icon name="message-slash" class="w-8 h-8 text-slate-300" style="duotone" />
            </div>
            <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2 tracking-tight uppercase">Sem Atendimentos</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-6 text-sm font-medium">Não foram encontradas sessões de chat com os critérios atuais.</p>
            <a href="{{ route('admin.chat.index') }}" class="inline-flex items-center gap-2 px-6 py-3 text-[10px] font-black text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all uppercase tracking-widest shadow-lg shadow-blue-500/20">
                <x-icon name="rotate-right" class="w-4 h-4" style="duotone" />
                Limpar Filtros
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
