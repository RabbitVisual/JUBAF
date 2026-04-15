@extends('layouts.app')

@section('title', 'Arquivo')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex justify-between pb-4 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Arquivo</h1>
        @can('create', \Modules\Secretaria\App\Models\SecretariaDocument::class)
            <a href="{{ route($routePrefix.'.create') }}" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg">Carregar</a>
        @endcan
    </div>
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm">
        <table class="min-w-full"><thead class="bg-gray-50 dark:bg-slate-900 text-xs uppercase"><tr><th class="px-4 py-3 text-left">Título</th><th class="px-4 py-3">Categoria</th><th class="px-4 py-3">Visibilidade</th><th class="px-4 py-3"></th></tr></thead>
            <tbody class="divide-y dark:divide-slate-700">@foreach($documents as $d)<tr><td class="px-4 py-3">{{ $d->title }}</td><td class="px-4 py-3">{{ $d->category }}</td><td class="px-4 py-3">{{ $d->is_public ? 'Público' : 'Interno' }}</td><td class="px-4 py-3 text-right space-x-2">@can('download', $d)<a href="{{ route($routePrefix.'.download', $d) }}" class="text-indigo-600">Download</a>@endcan @can('delete', $d)<form action="{{ route($routePrefix.'.destroy', $d) }}" method="POST" class="inline" onsubmit="return confirm('Remover?');">@csrf @method('DELETE')<button class="text-red-600">Eliminar</button></form>@endcan</td></tr>@endforeach</tbody>
        </table>
    </div>
    {{ $documents->links() }}
</div>
@endsection
