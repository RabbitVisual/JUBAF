@extends('admin::layouts.admin')

@section('title', 'Detalhes do Log')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="magnifying-glass" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Detalhes do Evento</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 dark:hover:text-red-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <a href="{{ route('admin.audit.index') }}" class="hover:text-red-600 dark:hover:text-red-400 transition-colors italic">Auditoria</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Log #{{ $log->id }}</span>
            </nav>
        </div>
        <a href="{{ route('admin.audit.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 dark:focus:ring-slate-700 transition-colors">
            <x-icon name="arrow-left" class="w-5 h-5" style="duotone" />
            Voltar aos Logs
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Detalhes do Evento (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card Principal -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
                    <x-icon name="info" class="w-4 h-4 text-red-500" style="duotone" />
                    <h2 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Visão Geral</h2>
                </div>

                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Data & Hora</p>
                        <p class="text-base font-bold text-gray-900 dark:text-white">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Ação Realizada</p>
                        @php
                            $actionColor = match(true) {
                                Str::contains($log->action, 'create') => 'emerald',
                                Str::contains($log->action, 'update') => 'blue',
                                Str::contains($log->action, 'delete') => 'red',
                                default => 'slate'
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-{{ $actionColor }}-100 text-{{ $actionColor }}-700 dark:bg-{{ $actionColor }}-900/30 dark:text-{{ $actionColor }}-300 border border-{{ $actionColor }}-200 dark:border-{{ $actionColor }}-800/50">
                            {{ $log->action }}
                        </span>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Módulo</p>
                        <p class="text-base font-bold text-gray-900 dark:text-white capitalize">{{ $log->module ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Modelo de Dados</p>
                        <p class="text-base font-mono font-medium text-gray-600 dark:text-gray-300 break-all">
                            {{ $log->model_type ?? '-' }}
                            <span class="text-red-500 font-bold ml-1">#{{ $log->model_id ?? '' }}</span>
                        </p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Descrição do Evento</p>
                        <div class="bg-gray-50 dark:bg-slate-900/50 rounded-xl p-4 border border-gray-100 dark:border-slate-700/50">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $log->description ?? 'Sem descrição adicional disponível.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alterações de Dados -->
            @if($log->old_values || $log->new_values)
            <div class="space-y-6">
                @if($log->old_values)
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-red-100 dark:border-red-900/20 overflow-hidden font-sans">
                    <div class="px-6 py-4 border-b border-red-50 dark:border-red-900/20 bg-red-50/50 dark:bg-red-900/10 flex items-center gap-2">
                        <x-icon name="minus-circle" class="w-4 h-4 text-red-500" style="duotone" />
                        <h3 class="text-[10px] font-black text-red-600 dark:text-red-400 uppercase tracking-widest italic">Valores Anteriores</h3>
                    </div>
                    <div class="p-0">
                        <div class="bg-slate-950 p-6 overflow-x-auto">
                            <pre class="text-xs font-mono text-red-300 leading-relaxed">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
                @endif

                @if($log->new_values)
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-emerald-100 dark:border-emerald-900/20 overflow-hidden font-sans">
                    <div class="px-6 py-4 border-b border-emerald-50 dark:border-emerald-900/20 bg-emerald-50/50 dark:bg-emerald-900/10 flex items-center gap-2">
                        <x-icon name="plus-circle" class="w-4 h-4 text-emerald-500" style="duotone" />
                        <h3 class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest italic">Novos Valores</h3>
                    </div>
                    <div class="p-0">
                        <div class="bg-slate-950 p-6 overflow-x-auto">
                            <pre class="text-xs font-mono text-emerald-300 leading-relaxed">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Coluna Lateral (1/3) -->
        <div class="space-y-6">
            <!-- Card Usuário -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
                    <x-icon name="user" class="w-4 h-4 text-red-500" style="duotone" />
                    <h2 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Responsável</h2>
                </div>

                <div class="p-8 flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center text-2xl font-black text-slate-600 dark:text-white shadow-inner mb-4">
                        {{ $log->user ? substr($log->user->name, 0, 1) : 'S' }}
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                        {{ $log->user->name ?? 'Sistema' }}
                    </h3>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-6">
                        {{ $log->user ? 'Administrador' : 'Processo Automático' }}
                    </p>

                    <div class="w-full space-y-3 pt-6 border-t border-gray-100 dark:border-slate-700/50 text-left">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">E-mail</p>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $log->user->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">ID Interno</p>
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-mono font-bold bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300">
                                #{{ $log->user->id ?? '0' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Contexto Técnico -->
            <div class="bg-slate-900 rounded-3xl shadow-lg border border-slate-700 overflow-hidden font-sans relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/10 rounded-full -mr-16 -mt-16 blur-xl"></div>

                <div class="px-6 py-4 border-b border-slate-700 bg-slate-800/50 flex items-center gap-2 relative z-10">
                    <x-icon name="server" class="w-4 h-4 text-red-400" style="duotone" />
                    <h2 class="text-[10px] font-black text-white uppercase tracking-widest italic">Contexto Técnico</h2>
                </div>

                <div class="p-6 space-y-6 relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic mb-1">Endereço IP</p>
                        <p class="text-sm font-mono text-red-200 bg-red-500/10 px-3 py-2 rounded-lg border border-red-500/20">
                            {{ $log->ip_address ?? 'Endereço Indisponível' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic mb-1">User Agent</p>
                        <div class="h-24 overflow-y-auto custom-scrollbar pr-2">
                            <p class="text-xs font-mono text-slate-400 leading-relaxed break-words">
                                {{ $log->user_agent ?? 'Não registrado.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.02);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(248, 113, 113, 0.2);
        border-radius: 10px;
    }
</style>
@endsection
