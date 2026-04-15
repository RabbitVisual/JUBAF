@extends('layouts.app')

@section('title', 'Gerenciamento de API')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="code" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Gerenciamento de API</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">API & Integrações</span>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 dark:focus:ring-slate-700 transition-colors">
                <x-icon name="arrow-left" class="w-5 h-5" style="duotone" />
                Voltar
            </a>
            <a href="{{ route('admin.api.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest text-[10px]">
                <x-icon name="plus" class="w-5 h-5" style="duotone" />
                Novo Token
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total de Tokens</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-1 italic tracking-tighter">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800/50">
                    <x-icon name="key" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" style="duotone" />
                </div>
            </div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ativos</p>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1 italic tracking-tighter">{{ $stats['ativos'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800/50">
                    <x-icon name="circle-check" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" style="duotone" />
                </div>
            </div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-slate-800 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Revogados/Inativos</p>
                    <p class="text-2xl font-black text-red-600 dark:text-red-400 mt-1 italic tracking-tighter">{{ $stats['inativos'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-100 dark:border-red-800/50">
                    <x-icon name="ban" class="w-6 h-6 text-red-600 dark:text-red-400" style="duotone" />
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
            <x-icon name="list" class="w-4 h-4 text-indigo-500" style="duotone" />
            <h3 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Listagem de Tokens</h3>
        </div>

        <div class="overflow-x-auto font-sans">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 font-sans">
                <thead class="text-[10px] font-black text-slate-400 uppercase bg-gray-50/50 dark:bg-slate-900/50 tracking-widest font-sans">
                    <tr>
                        <th scope="col" class="px-6 py-5">Nome / Identificação</th>
                        <th scope="col" class="px-6 py-5">Usuário Responsável</th>
                        <th scope="col" class="px-6 py-5 text-center">Último Uso</th>
                        <th scope="col" class="px-6 py-5 text-center">Status</th>
                        <th scope="col" class="px-6 py-5 text-right font-sans">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 font-sans">
                    @forelse($tokens as $token)
                    <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors group">
                        <td class="px-6 py-5 font-sans">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white dark:bg-slate-900 rounded-xl flex items-center justify-center text-indigo-500 shadow-sm border border-gray-100 dark:border-slate-800 group-hover:scale-110 transition-transform">
                                    <x-icon name="key" class="w-5 h-5 font-sans" style="duotone" />
                                </div>
                                <div>
                                    <div class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight group-hover:text-indigo-600 transition-colors font-sans">
                                        {{ $token->name }}
                                    </div>
                                    <div class="text-[10px] font-mono font-bold text-gray-400 uppercase tracking-widest mt-1">
                                        Criado em: {{ $token->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 font-sans">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300">
                                    {{ substr($token->user->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">{{ $token->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center font-sans">
                            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400">
                                {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Nunca utilizado' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center font-sans">
                            @if($token->is_active)
                                <span class="px-3 py-1 text-[9px] font-black rounded-lg border bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800 uppercase tracking-widest">
                                    Ativo
                                </span>
                            @else
                                <span class="px-3 py-1 text-[9px] font-black rounded-lg border bg-red-50 text-red-700 border-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800 uppercase tracking-widest">
                                    Inativo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right font-sans">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all font-sans">
                                <a href="{{ route('admin.api.show', $token->id) }}" class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-lg hover:bg-indigo-100 transition-all shadow-sm font-sans" title="Ver Detalhes">
                                    <x-icon name="eye" class="w-4 h-4 font-sans" style="duotone" />
                                </a>
                                <form action="{{ route('admin.api.revoke', $token->id) }}" method="POST" onsubmit="return confirm('Deseja realmente revogar este token?')" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded-lg hover:bg-red-100 transition-all shadow-sm font-sans" title="Revogar">
                                        <x-icon name="ban" class="w-4 h-4 font-sans" style="duotone" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-medium italic">Nenhum token de API encontrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tokens->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/30">
            {{ $tokens->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
