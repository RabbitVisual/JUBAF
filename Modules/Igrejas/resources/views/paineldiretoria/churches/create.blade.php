@extends($layout)

@section('title', 'Nova congregação')

@section('content')
<div class="mx-auto max-w-3xl space-y-8 pb-10">
    @include('igrejas::paineldiretoria.partials.subnav', ['active' => 'list'])

    <div>
        <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-cyan-700 hover:underline dark:text-cyan-400">
            <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
            Voltar à lista
        </a>
        <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Nova congregação</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Registo canónico — os dados aparecem nos painéis de jovens, líderes e pastores vinculados.</p>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-200">
            <p class="font-semibold">Corrija os campos assinalados.</p>
        </div>
    @endif

    <form action="{{ route($routePrefix.'.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
            @include('igrejas::paineldiretoria.churches._form', ['church' => $church])
        </div>
        <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 dark:border-slate-600 dark:text-white dark:hover:bg-slate-700">Cancelar</a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">
                <x-icon name="check" class="h-4 w-4" style="solid" />
                Guardar
            </button>
        </div>
    </form>
</div>
@endsection
