@extends($layout)

@section('title', $minute->title)

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'atas'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Secretaria · Atas</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">{{ $minute->title }}</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Estado: <strong class="text-gray-900 dark:text-white">{{ $minute->status }}</strong>@if($minute->published_at) · Publicada {{ $minute->published_at->format('d/m/Y H:i') }}@endif</p>
            @if($minute->protocol_number)
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Protocolo: <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-gray-800 dark:bg-slate-900 dark:text-gray-200">{{ $minute->protocol_number }}</code></p>
            @endif
            @if($minute->content_checksum)
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Integridade (SHA-256): <code class="break-all rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[11px] text-gray-800 dark:bg-slate-900 dark:text-gray-200">{{ $minute->content_checksum }}</code></p>
            @endif
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route($routePrefix.'.index') }}" class="text-sm font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Lista</a>
            @can('update', $minute)<a href="{{ route($routePrefix.'.edit', $minute) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Editar</a>@endcan
            @can('downloadPdf', $minute)<a href="{{ route($routePrefix.'.pdf', $minute) }}" class="text-sm font-semibold text-emerald-700 hover:underline dark:text-emerald-400">PDF</a>@endcan
        </div>
    </div>

    <div class="prose prose-sm max-w-none rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:prose-invert dark:border-slate-700 dark:bg-slate-800 sm:prose-base">{!! $minute->body !!}</div>

    @if($minute->attachments->isNotEmpty())
        <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Anexos</h2>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach($minute->attachments as $att)
                    <li class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 pb-2 last:border-0 dark:border-slate-700">
                        <span class="text-gray-800 dark:text-gray-200">
                            {{ $att->original_name ?? basename($att->path) }}
                            <span class="text-xs text-gray-500">({{ str_replace('_', ' ', $att->kind) }})</span>
                        </span>
                        <span class="flex gap-2">
                            @can('view', $minute)
                                <a href="{{ route($routePrefix.'.attachments.download', [$minute, $att]) }}" class="text-sm font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Descarregar</a>
                            @endcan
                            @can('update', $minute)
                                <form action="{{ route($routePrefix.'.attachments.destroy', [$minute, $att]) }}" method="POST" onsubmit="return confirm('Remover este anexo?');" class="inline">@csrf @method('DELETE')<button type="submit" class="text-sm font-semibold text-red-600 hover:underline">Eliminar</button></form>
                            @endcan
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-wrap gap-2">
        @can('submit', $minute)<form action="{{ route($routePrefix.'.submit', $minute) }}" method="POST">@csrf<button type="submit" class="rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-amber-700">Enviar aprovação</button></form>@endcan
        @can('approve', $minute)<form action="{{ route($routePrefix.'.approve', $minute) }}" method="POST">@csrf<button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">Aprovar</button></form>@endcan
        @can('publish', $minute)<form action="{{ route($routePrefix.'.publish', $minute) }}" method="POST" onsubmit="return confirm('Publicar?');">@csrf<button type="submit" class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600">Publicar</button></form>@endcan
        @can('archive', $minute)<form action="{{ route($routePrefix.'.archive', $minute) }}" method="POST" onsubmit="return confirm('Arquivar esta ata? Fica só leitura.');">@csrf<button type="submit" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-800 shadow-sm transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700">Arquivar</button></form>@endcan
    </div>
</div>
@endsection
