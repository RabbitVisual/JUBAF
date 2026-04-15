@extends($layout)

@section('title', 'Devocionais')

@section('content')
<div class="space-y-6 pb-12 max-w-6xl">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <x-icon name="book-open" class="w-8 h-8 text-amber-600" style="duotone" />
                Devocionais
            </h1>
            <p class="text-sm text-gray-500 mt-1">Publicação em /devocionais</p>
        </div>
        @can('create', \App\Models\Devotional::class)
        <a href="{{ route($routePrefix.'.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-600 text-white font-semibold hover:bg-amber-700 text-sm">
            <x-icon name="plus" class="w-4 h-4" /> Novo
        </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-slate-900/50 text-left text-xs font-bold uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Título</th>
                    <th class="px-4 py-3">Data</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3 text-end">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($rows as $row)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row->title }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $row->devotional_date?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($row->status === \App\Models\Devotional::STATUS_PUBLISHED)
                                <span class="text-emerald-700 dark:text-emerald-400 font-semibold">Publicado</span>
                            @else
                                <span class="text-gray-500">Rascunho</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-end space-x-2">
                            @can('update', $row)
                            <a href="{{ route($routePrefix.'.edit', $row) }}" class="text-amber-700 hover:underline font-medium">Editar</a>
                            @endcan
                            @can('delete', $row)
                            <form action="{{ route($routePrefix.'.destroy', $row) }}" method="post" class="inline" onsubmit="return confirm('Remover?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline font-medium">Excluir</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-gray-500">Nenhum devocional.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($rows->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">{{ $rows->links() }}</div>
        @endif
    </div>
</div>
@endsection
