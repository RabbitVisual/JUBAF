@extends('admin::components.layouts.master')

@section('title', 'Conselho — Membros')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Membros do conselho</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Efetivos e suplentes.</p>
            </div>
            @if(auth()->user()->canAccess('council_manage'))
                <a href="{{ route('admin.council.members.create') }}" class="inline-flex px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Novo membro</a>
            @endif
        </div>
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 overflow-hidden">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-slate-800">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Nome</th>
                        <th class="px-4 py-3 text-left font-semibold">Tipo</th>
                        <th class="px-4 py-3 text-left font-semibold">Ativo</th>
                        <th class="px-4 py-3 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($members as $m)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $m->full_name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $m->kind === 'supplement' ? 'Suplente' : 'Efetivo' }}</td>
                            <td class="px-4 py-3">{{ $m->is_active ? 'Sim' : 'Não' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.council.members.show', $m) }}" class="text-blue-600 text-xs hover:underline">Ver</a>
                                @if(auth()->user()->canAccess('council_manage'))
                                    <a href="{{ route('admin.council.members.edit', $m) }}" class="text-blue-600 text-xs hover:underline ml-2">Editar</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Sem membros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $members->links() }}</div>
    </div>
@endsection
