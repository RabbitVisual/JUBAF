@extends('admin::layouts.admin')

@section('title', 'Reunião')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $meeting->title ?: $meeting->type }}</h1>
        <a href="{{ route($routePrefix.'.index') }}" class="text-sm text-indigo-600">Voltar</a>
    </div>
    <dl class="text-sm space-y-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-6">
        <div><dt class="text-gray-500">Início</dt><dd class="font-medium">{{ $meeting->starts_at->format('d/m/Y H:i') }}</dd></div>
        <div><dt class="text-gray-500">Estado</dt><dd>{{ $meeting->status }}</dd></div>
        @if($meeting->location)<div><dt class="text-gray-500">Local</dt><dd>{{ $meeting->location }}</dd></div>@endif
    </dl>
    @can('update', $meeting)
        <a href="{{ route($routePrefix.'.edit', $meeting) }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm">Editar</a>
    @endcan
</div>
@endsection
