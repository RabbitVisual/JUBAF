@extends('layouts.app')

@section('title', 'Convocatórias')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex justify-between pb-4 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Convocatórias</h1>
        @can('create', \Modules\Secretaria\App\Models\Convocation::class)
            <a href="{{ route($routePrefix.'.create') }}" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg">Nova</a>
        @endcan
    </div>
    <form method="get" class="flex gap-2"><select name="status" class="rounded-lg border dark:bg-slate-900 text-sm"><option value="">Todos</option>@foreach(['draft'=>'Rascunho','pending_approval'=>'Pendente','approved'=>'Aprovada','published'=>'Publicada'] as $k=>$l)<option value="{{ $k }}" @selected(($filters['status']??'')==$k)>{{ $l }}</option>@endforeach</select><button class="px-3 py-2 text-sm bg-gray-100 dark:bg-slate-700 rounded-lg">Filtrar</button></form>
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm">
        <table class="min-w-full"><thead class="bg-gray-50 dark:bg-slate-900 text-xs uppercase"><tr><th class="px-4 py-3 text-left">Título</th><th class="px-4 py-3">Assembleia</th><th class="px-4 py-3">Estado</th><th class="px-4 py-3"></th></tr></thead>
            <tbody class="divide-y dark:divide-slate-700">@foreach($convocations as $c)<tr><td class="px-4 py-3">{{ $c->title }}</td><td class="px-4 py-3">{{ $c->assembly_at->format('d/m/Y H:i') }}</td><td class="px-4 py-3">{{ $c->status }}</td><td class="px-4 py-3 text-right"><a href="{{ route($routePrefix.'.show', $c) }}" class="text-indigo-600">Ver</a></td></tr>@endforeach</tbody>
        </table>
    </div>
    {{ $convocations->links() }}
</div>
@endsection
