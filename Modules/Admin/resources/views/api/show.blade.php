@extends('admin::layouts.admin')

@section('title', 'Detalhes do Token de API')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="eye" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Detalhes do Token</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <a href="{{ route('admin.api.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors italic">API</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Visão Geral</span>
            </nav>
        </div>
        <a href="{{ route('admin.api.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 dark:focus:ring-slate-700 transition-colors">
            <x-icon name="arrow-left" class="w-5 h-5" style="duotone" />
            Voltar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Detalhes Principais -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
                    <x-icon name="info" class="w-4 h-4 text-indigo-500" style="duotone" />
                    <h2 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Informações Gerais</h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Nome do Token</p>
                            <p class="text-base font-bold text-gray-900 dark:text-white">{{ $token->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Usuário Responsável</p>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300">
                                    {{ substr($token->user->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $token->user->name }}</span>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Descrição</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-800">
                                {{ $token->description ?? 'Nenhuma descrição fornecida.' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-700/50">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Criado em</p>
                                <p class="text-sm font-mono font-bold text-gray-700 dark:text-gray-300">{{ $token->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Último Uso</p>
                                <p class="text-sm font-mono font-bold text-gray-700 dark:text-gray-300">
                                    {{ $token->last_used_at ? $token->last_used_at->format('d/m/Y H:i') : 'Nunca utilizado' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-1">Expira em</p>
                                <p class="text-sm font-mono font-bold text-gray-700 dark:text-gray-300">
                                    {{ $token->expires_at ? $token->expires_at->format('d/m/Y') : 'Nunca expira' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configurações de Segurança -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
                    <x-icon name="shield-check" class="w-4 h-4 text-indigo-500" style="duotone" />
                    <h2 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Segurança e Permissões</h2>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Permissões (Abilities)</p>
                        <div class="flex flex-wrap gap-2">
                            @if(in_array('*', $token->abilities))
                                <span class="px-3 py-1 text-xs font-bold rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100 dark:bg-indigo-900/20 dark:text-indigo-400 dark:border-indigo-800">
                                    Acesso Total (*)
                                </span>
                            @else
                                @foreach($token->abilities as $ability)
                                    <span class="px-3 py-1 text-xs font-bold rounded-lg bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600">
                                        {{ $ability }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">IP Whitelist</p>
                            @if(!empty($token->ip_whitelist))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($token->ip_whitelist as $ip)
                                        <span class="px-2 py-1 text-xs font-mono rounded bg-gray-100 text-gray-700 border border-gray-200 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-700">
                                            {{ $ip }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-sm text-gray-500 italic">Todos os IPs permitidos</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Rate Limit</p>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                {{ $token->rate_limit }} req/min
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
                    <x-icon name="cog" class="w-4 h-4 text-indigo-500" style="duotone" />
                    <h2 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Ações</h2>
                </div>

                <div class="p-6 space-y-4">
                    @if($token->is_active)
                    <form action="{{ route('admin.api.revoke', $token->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 focus:ring-4 focus:ring-amber-200 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800 dark:hover:bg-amber-900/30 dark:focus:ring-amber-800 transition-colors uppercase tracking-widest" onclick="return confirm('Tem certeza que deseja revogar este token?')">
                            <x-icon name="ban" class="w-5 h-5" style="duotone" />
                            Revogar Token
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('admin.api.regenerate', $token->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 focus:ring-4 focus:ring-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800 dark:hover:bg-blue-900/30 dark:focus:ring-blue-800 transition-colors uppercase tracking-widest" onclick="return confirm('Tem certeza? Um novo token será gerado e este será desativado.')">
                            <x-icon name="arrow-path" class="w-5 h-5" style="duotone" />
                            Regenerar Token
                        </button>
                    </form>

                    <form action="{{ route('admin.api.destroy', $token->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-red-700 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 focus:ring-4 focus:ring-red-200 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800 dark:hover:bg-red-900/30 dark:focus:ring-red-800 transition-colors uppercase tracking-widest" onclick="return confirm('Tem certeza que deseja remover permanentemente este token?')">
                            <x-icon name="trash" class="w-5 h-5" style="duotone" />
                            Remover Token
                        </button>
                    </form>

                    <hr class="border-gray-100 dark:border-slate-700">

                    <a href="{{ route('api.documentation') }}" target="_blank" class="block w-full text-center px-4 py-3 text-sm font-bold text-slate-600 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 focus:ring-4 focus:ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 dark:focus:ring-slate-800 transition-colors uppercase tracking-widest">
                        Ver Documentação
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
