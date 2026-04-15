@extends('admin::layouts.admin')

@section('title', $minute->title)

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex justify-between flex-wrap gap-3">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $minute->title }}</h1>
        <div class="flex gap-2">
            <a href="{{ route($routePrefix.'.index') }}" class="text-sm text-indigo-600">Lista</a>
            @can('update', $minute)<a href="{{ route($routePrefix.'.edit', $minute) }}" class="text-sm text-gray-600">Editar</a>@endcan
            @can('downloadPdf', $minute)<a href="{{ route($routePrefix.'.pdf', $minute) }}" class="text-sm text-violet-600">PDF</a>@endcan
        </div>
    </div>
    <div class="text-sm text-gray-500">Estado: <strong>{{ $minute->status }}</strong>@if($minute->published_at) · Publicada {{ $minute->published_at->format('d/m/Y H:i') }}@endif</div>
    <div class="prose dark:prose-invert max-w-none border rounded-xl p-6 bg-white dark:bg-slate-800">{!! $minute->body !!}</div>
    @if($minute->attachments->isNotEmpty())
        <div class="border rounded-xl p-6 bg-white dark:bg-slate-800">
            <h2 class="font-semibold text-gray-900 dark:text-white text-sm">Anexos</h2>
            <ul class="mt-2 space-y-2 text-sm">
                @foreach($minute->attachments as $att)
                    <li class="flex flex-wrap justify-between gap-2">
                        <span>{{ $att->original_name ?? $att->path }} <span class="text-gray-500">({{ $att->kind }})</span></span>
                        <span class="flex gap-2">
                            @can('view', $minute)<a href="{{ route($routePrefix.'.attachments.download', [$minute, $att]) }}" class="text-indigo-600 text-sm">Descarregar</a>@endcan
                            @can('update', $minute)<form action="{{ route($routePrefix.'.attachments.destroy', [$minute, $att]) }}" method="POST" class="inline" onsubmit="return confirm('Remover?');">@csrf @method('DELETE')<button type="submit" class="text-red-600 text-sm">Eliminar</button></form>@endcan
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="flex flex-wrap gap-2">
        @can('submit', $minute)<form action="{{ route($routePrefix.'.submit', $minute) }}" method="POST">@csrf<button class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm">Enviar aprovação</button></form>@endcan
        @can('approve', $minute)<form action="{{ route($routePrefix.'.approve', $minute) }}" method="POST">@csrf<button class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">Aprovar</button></form>@endcan
        @can('publish', $minute)<form action="{{ route($routePrefix.'.publish', $minute) }}" method="POST" onsubmit="return confirm('Publicar?');">@csrf<button class="px-4 py-2 bg-violet-600 text-white rounded-lg text-sm">Publicar</button></form>@endcan
    </div>
</div>
@endsection
