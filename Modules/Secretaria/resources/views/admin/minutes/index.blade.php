@extends('admin::layouts.admin')

@section('title', 'Atas')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex flex-wrap justify-between gap-3 pb-4 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Atas</h1>
        @can('create', \Modules\Secretaria\App\Models\Minute::class)
            <a href="{{ route($routePrefix.'.create') }}" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg">Nova ata</a>
        @endcan
    </div>
    <form method="get" class="flex gap-2"><select name="status" class="rounded-lg border dark:bg-slate-900 text-sm"><option value="">Todos</option>@foreach(['draft'=>'Rascunho','pending_signatures'=>'Assinaturas pendentes','published'=>'Publicada','archived'=>'Arquivada'] as $k=>$l)<option value="{{ $k }}" @selected(($filters['status']??'')==$k)>{{ $l }}</option>@endforeach</select><button class="px-3 py-2 text-sm bg-gray-100 dark:bg-slate-700 rounded-lg">Filtrar</button></form>
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm">
        <table class="min-w-full"><thead class="bg-gray-50 dark:bg-slate-900 text-xs uppercase text-gray-500"><tr><th class="px-4 py-3 text-left">Título</th><th class="px-4 py-3">Estado</th><th class="px-4 py-3"></th></tr></thead>
            <tbody class="divide-y dark:divide-slate-700">@foreach($minutes as $m)<tr><td class="px-4 py-3">{{ $m->title }}</td><td class="px-4 py-3">{{ $m->status }}</td><td class="px-4 py-3 text-right space-x-2"><a href="{{ route($routePrefix.'.show', $m) }}" class="text-indigo-600">Ver</a>@can('update',$m)<a href="{{ route($routePrefix.'.edit', $m) }}" class="text-gray-600">Editar</a>@endcan</td></tr>@endforeach</tbody>
        </table>
    </div>
    {{ $minutes->links() }}
</div>
@endsection
