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
            @if($minute->document_hash || $minute->content_checksum)
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Integridade (SHA-256): <code class="break-all rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[11px] text-gray-800 dark:bg-slate-900 dark:text-gray-200">{{ $minute->document_hash ?? $minute->content_checksum }}</code></p>
            @endif
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route($routePrefix.'.index') }}" class="text-sm font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Lista</a>
            @can('update', $minute)<a href="{{ route($routePrefix.'.edit', $minute) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Editar</a>@endcan
            @can('downloadPdf', $minute)<a href="{{ route($routePrefix.'.pdf', $minute) }}" class="text-sm font-semibold text-emerald-700 hover:underline dark:text-emerald-400">PDF</a>@endcan
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <ol class="mb-6 items-center gap-4 space-y-4 text-sm font-medium text-gray-500 dark:text-gray-400 sm:flex sm:space-y-0">
            <li class="{{ $minute->status === 'draft' ? 'text-emerald-700 dark:text-emerald-400' : '' }}">1. Rascunho</li>
            <li class="{{ $minute->status === 'pending_signatures' ? 'text-emerald-700 dark:text-emerald-400' : '' }}">2. Assinaturas</li>
            <li class="{{ $minute->status === 'published' ? 'text-emerald-700 dark:text-emerald-400' : '' }}">3. Publicada</li>
        </ol>
        <div class="prose prose-sm max-w-none dark:prose-invert sm:prose-base">{!! $minute->content !!}</div>
    </div>

    @if(isset($requiredSignerRoles))
        <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Timeline de assinaturas</h2>
            <ul class="mt-4 space-y-2">
                @foreach($requiredSignerRoles as $role)
                    @php
                        $signature = $minute->signatures->firstWhere('role_at_the_time', $role);
                    @endphp
                    <li class="flex items-center justify-between gap-4 rounded-xl border border-gray-100 px-3 py-2 dark:border-slate-700">
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $role }}</span>
                        <span class="text-xs {{ $signature ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400' }}">
                            {{ $signature ? 'Assinado em '.$signature->signed_at?->format('d/m/Y H:i') : 'Pendente' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

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
        @can('submit', $minute)<form action="{{ route($routePrefix.'.submit', $minute) }}" method="POST">@csrf<button type="submit" class="rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-amber-700">Solicitar assinaturas</button></form>@endcan
        @can('sign', $minute)
            <form action="{{ route($routePrefix.'.sign', $minute) }}" method="POST" class="flex items-center gap-2">
                @csrf
                <input type="password" name="password" placeholder="Senha atual" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600">Assinar ata</button>
            </form>
        @endcan
        @can('archive', $minute)<form action="{{ route($routePrefix.'.archive', $minute) }}" method="POST" onsubmit="return confirm('Arquivar esta ata? Fica só leitura.');">@csrf<button type="submit" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-800 shadow-sm transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700">Arquivar</button></form>@endcan
    </div>
</div>
@endsection
