@extends('admin::layouts.admin')

@section('title', 'Detalhes da Notificação')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon module="notificacoes" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Detalhes da Notificação</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <a href="{{ route('admin.notificacoes.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors italic">Notificações</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Detalhes</span>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.notificacoes.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 dark:focus:ring-slate-700 transition-colors">
                <x-icon name="arrow-left" class="w-5 h-5" style="duotone" />
                Voltar
            </a>
            <form action="{{ route('admin.notificacoes.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja deletar esta notificação?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 transition-all shadow-lg shadow-red-500/20 uppercase tracking-widest text-[10px]">
                    <x-icon name="trash" class="w-5 h-5" style="duotone" />
                    Excluir
                </button>
            </form>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800" role="alert">
        <div class="flex items-center gap-2">
            <x-icon name="circle-check" class="w-5 h-5" style="duotone" />
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Content Card -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
            <x-icon name="circle-info" class="w-4 h-4 text-indigo-500" style="duotone" />
            <h3 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Informações da Notificação</h3>
        </div>

        <div class="p-6 md:p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Coluna Esquerda -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Tipo</label>
                        @php
                            $typeCores = [
                                'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                'success' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
                                'warning' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
                                'danger' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                'error' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                'alert' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                                'system' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300'
                            ];
                            $typeClass = $typeCores[$notification->type] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide {{ $typeClass }}">
                            {{ $notification->type_texto }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Título</label>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $notification->title }}</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Módulo</label>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500">
                                <x-icon name="layer-group" class="w-4 h-4" style="duotone" />
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $notification->module_source ?? 'Geral' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Coluna Direita -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Status</label>
                        <div class="flex flex-col gap-2">
                            @if($notification->is_read)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 w-fit">
                                    <x-icon name="check-circle" class="w-3.5 h-3.5" style="duotone" />
                                    Lida
                                </span>
                                @if($notification->read_at)
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                        Em {{ $notification->read_at->format('d/m/Y \à\s H:i') }}
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 w-fit">
                                    <x-icon name="clock" class="w-3.5 h-3.5" style="duotone" />
                                    Não lida
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Destinatário</label>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                @if($notification->user)
                                    {{ strtoupper(substr($notification->user->name, 0, 2)) }}
                                @else
                                    <x-icon name="users" class="w-5 h-5" />
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $notification->user->name ?? ($notification->role ? ucfirst($notification->role) : 'Todos os Usuários') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $notification->user ? 'Usuário Específico' : ($notification->role ? 'Grupo de Permissão' : 'Transmissão Geral') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Data de Envio</label>
                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                            <x-icon name="calendar" class="w-4 h-4 text-gray-400" style="duotone" />
                            <span class="text-sm font-medium">{{ $notification->created_at->format('d/m/Y \à\s H:i') }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $notification->created_at->diffForHumans() }})</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensagem -->
            <div class="pt-6 border-t border-gray-100 dark:border-slate-700/50">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-3">Mensagem</label>
                <div class="p-6 bg-gray-50 dark:bg-slate-900/50 rounded-2xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap font-sans text-sm">
                    {{ $notification->message }}
                </div>
            </div>

            <!-- Dados Adicionais -->
            @if($notification->data || $notification->action_url)
            <div class="pt-6 border-t border-gray-100 dark:border-slate-700/50">
                <div class="flex flex-col gap-6">
                    @if($notification->action_url)
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">URL de Ação</label>
                        <a href="{{ $notification->action_url }}" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium transition-colors bg-indigo-50 dark:bg-indigo-900/20 px-4 py-2 rounded-xl">
                            <x-icon name="link" class="w-4 h-4" />
                            {{ $notification->action_url }}
                            <x-icon name="arrow-up-right-from-square" class="w-3 h-3" />
                        </a>
                    </div>
                    @endif

                    @if($notification->data)
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Dados (JSON)</label>
                        <div class="p-4 bg-slate-800 rounded-xl border border-slate-700 overflow-x-auto">
                            <pre class="text-xs text-emerald-400 font-mono">{{ json_encode($notification->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Info Broadcasting -->
            <div class="mt-4 p-4 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/10 dark:to-purple-900/10 border border-indigo-100 dark:border-indigo-800/30">
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm">
                        <x-icon name="signal-stream" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" style="duotone" />
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-indigo-900 dark:text-indigo-200 mb-1">Broadcasting em Tempo Real</h4>
                        <p class="text-xs text-indigo-800/80 dark:text-indigo-300/80 mb-3 leading-relaxed">
                            Esta notificação foi processada pelo sistema de eventos e entregue via WebSocket para os canais inscritos.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @if($notification->user_id)
                                <span class="px-2 py-1 rounded text-[10px] font-mono font-medium bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                                    private-user.{{ $notification->user_id }}
                                </span>
                            @elseif($notification->role)
                                <span class="px-2 py-1 rounded text-[10px] font-mono font-medium bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                                    presence-role.{{ $notification->role }}
                                </span>
                            @else
                                <span class="px-2 py-1 rounded text-[10px] font-mono font-medium bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                                    public-notifications
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
