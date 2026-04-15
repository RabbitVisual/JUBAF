@extends($layout)

@section('title', 'Editar convocatória')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'convocatorias'])

    <div class="border-b border-gray-200 pb-6 dark:border-slate-700">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Secretaria · Convocatórias</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Editar convocatória</h1>
    </div>

    <form action="{{ route($routePrefix.'.update', $convocation) }}" method="POST" class="mx-auto max-w-2xl space-y-6 rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        @csrf @method('PUT')
        @include('secretaria::paineldiretoria.convocations._form', ['convocation' => $convocation])
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">Atualizar</button>
    </form>
    <div class="mx-auto flex max-w-2xl flex-wrap gap-2">
        @if($convocation->status === 'draft')<form action="{{ route($routePrefix.'.submit', $convocation) }}" method="POST">@csrf<button type="submit" class="rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-amber-700">Enviar aprovação</button></form>@endif
        @can('approve', $convocation)<form action="{{ route($routePrefix.'.approve', $convocation) }}" method="POST">@csrf<button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">Aprovar</button></form>@endcan
        @can('publish', $convocation)<form action="{{ route($routePrefix.'.publish', $convocation) }}" method="POST">@csrf<button type="submit" class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600">Publicar</button></form>@endcan
    </div>
</div>
@endsection
