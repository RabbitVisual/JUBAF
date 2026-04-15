@extends($layout)

@section('title', $convocation->title)

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'convocatorias'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Secretaria · Convocatórias</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">{{ $convocation->title }}</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Assembleia: {{ $convocation->assembly_at->format('d/m/Y H:i') }} · Estado: <strong class="text-gray-900 dark:text-white">{{ $convocation->status }}</strong></p>
        </div>
        <a href="{{ route($routePrefix.'.index') }}" class="inline-flex shrink-0 text-sm font-semibold text-emerald-700 hover:underline dark:text-emerald-400">← Lista</a>
    </div>

    <div class="prose prose-sm max-w-none rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:prose-invert dark:border-slate-700 dark:bg-slate-800 sm:prose-base">{!! $convocation->body !!}</div>

    <div class="flex flex-wrap gap-2">
        @can('update', $convocation)<a href="{{ route($routePrefix.'.edit', $convocation) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">Editar</a>@endcan
        @if($convocation->status === 'draft')<form action="{{ route($routePrefix.'.submit', $convocation) }}" method="POST">@csrf<button type="submit" class="rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-amber-700">Enviar aprovação</button></form>@endif
        @can('approve', $convocation)<form action="{{ route($routePrefix.'.approve', $convocation) }}" method="POST">@csrf<button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">Aprovar</button></form>@endcan
        @can('publish', $convocation)<form action="{{ route($routePrefix.'.publish', $convocation) }}" method="POST">@csrf<button type="submit" class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600">Publicar</button></form>@endcan
    </div>
</div>
@endsection
