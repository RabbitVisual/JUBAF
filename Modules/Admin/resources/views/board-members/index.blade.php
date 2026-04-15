@extends('layouts.app')

@section('title', 'Diretoria — membros')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="users" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Diretoria</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium">Membros</span>
            </nav>
        </div>
        @can('create', \App\Models\BoardMember::class)
        <a href="{{ route('admin.board-members.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 rounded-lg shadow-md transition-all">
            <x-icon name="plus" class="w-5 h-5" style="solid" />
            Novo membro
        </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 dark:border-emerald-800 dark:bg-emerald-900/20 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-900/50 text-left text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Ordem</th>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">Cargo</th>
                        <th class="px-4 py-3">Ativo</th>
                        <th class="px-4 py-3 text-end">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($members as $m)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $m->sort_order }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $m->full_name }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $m->public_title }}</td>
                            <td class="px-4 py-3">
                                @if($m->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">Sim</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-slate-700 dark:text-gray-300">Não</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end space-x-2">
                                @can('update', $m)
                                <a href="{{ route('admin.board-members.edit', $m) }}" class="text-indigo-600 hover:underline font-medium">Editar</a>
                                @endcan
                                @can('delete', $m)
                                <form action="{{ route('admin.board-members.destroy', $m) }}" method="post" class="inline" onsubmit="return confirm('Remover este membro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline font-medium">Excluir</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">Nenhum membro cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($members->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 dark:border-slate-700">{{ $members->links() }}</div>
        @endif
    </div>
</div>
@endsection
