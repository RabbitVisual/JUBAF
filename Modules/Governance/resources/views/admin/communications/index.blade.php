@extends('admin::components.layouts.master')

@section('title', 'Comunicados oficiais')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Comunicados</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Documentos para transparência no site.</p>
            </div>
            @if(auth()->user()->canAccess('governance_manage'))
                <a href="{{ route('admin.governance.communications.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700">Novo comunicado</a>
            @endif
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800 text-sm">
                <thead class="bg-gray-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Título</th>
                        <th class="px-4 py-3 text-left font-semibold">Estado</th>
                        <th class="px-4 py-3 text-left font-semibold">Atualizado</th>
                        <th class="px-4 py-3 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($items as $row)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row->title }}</td>
                            <td class="px-4 py-3">
                                @if($row->is_published && $row->published_at)
                                    <span class="text-emerald-600 dark:text-emerald-400 text-xs font-medium">Publicado</span>
                                @else
                                    <span class="text-gray-500 text-xs">Rascunho</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $row->updated_at?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                @if($row->is_published && $row->published_at)
                                    <a href="{{ route('public.transparency.communication', $row) }}" target="_blank" class="text-blue-600 text-xs hover:underline">Ver</a>
                                @endif
                                @if(auth()->user()->canAccess('governance_manage'))
                                    <a href="{{ route('admin.governance.communications.edit', $row) }}" class="text-blue-600 text-xs hover:underline">Editar</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Nenhum comunicado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $items->links() }}</div>
    </div>
@endsection
