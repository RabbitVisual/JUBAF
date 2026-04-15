@extends('layouts.app')

@section('title', $convocation->title)

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex justify-between"><h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $convocation->title }}</h1><a href="{{ route($routePrefix.'.index') }}" class="text-sm text-indigo-600">Lista</a></div>
    <p class="text-sm text-gray-500">Assembleia: {{ $convocation->assembly_at->format('d/m/Y H:i') }} · Estado: {{ $convocation->status }}</p>
    <div class="prose dark:prose-invert border rounded-xl p-6 bg-white dark:bg-slate-800">{!! $convocation->body !!}</div>
    <div class="flex flex-wrap gap-2">
        @can('update', $convocation)<a href="{{ route($routePrefix.'.edit', $convocation) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm">Editar</a>@endcan
        @if($convocation->status === 'draft')<form action="{{ route($routePrefix.'.submit', $convocation) }}" method="POST">@csrf<button class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm">Enviar aprovação</button></form>@endif
        @can('approve', $convocation)<form action="{{ route($routePrefix.'.approve', $convocation) }}" method="POST">@csrf<button class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">Aprovar</button></form>@endcan
        @can('publish', $convocation)<form action="{{ route($routePrefix.'.publish', $convocation) }}" method="POST">@csrf<button class="px-4 py-2 bg-violet-600 text-white rounded-lg text-sm">Publicar</button></form>@endcan
    </div>
</div>
@endsection
