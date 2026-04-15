@extends('layouts.app')

@section('title', 'Editar convocatória')

@section('content')
<div class="max-w-2xl space-y-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar</h1>
    <form action="{{ route($routePrefix.'.update', $convocation) }}" method="POST" class="space-y-4 bg-white dark:bg-slate-800 border rounded-xl p-6">@csrf @method('PUT')
        @include('secretaria::paineldiretoria.convocations._form', ['convocation' => $convocation])
        <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold">Atualizar</button>
    </form>
    <div class="flex flex-wrap gap-2">
        @if($convocation->status === 'draft')<form action="{{ route($routePrefix.'.submit', $convocation) }}" method="POST">@csrf<button class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm">Enviar aprovação</button></form>@endif
        @can('approve', $convocation)<form action="{{ route($routePrefix.'.approve', $convocation) }}" method="POST">@csrf<button class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">Aprovar</button></form>@endcan
        @can('publish', $convocation)<form action="{{ route($routePrefix.'.publish', $convocation) }}" method="POST">@csrf<button class="px-4 py-2 bg-violet-600 text-white rounded-lg text-sm">Publicar</button></form>@endcan
    </div>
</div>
@endsection
