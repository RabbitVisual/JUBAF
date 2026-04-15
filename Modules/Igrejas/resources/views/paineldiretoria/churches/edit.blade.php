@extends($layout)

@section('title', 'Editar — '.$church->name)

@section('content')
<div class="mx-auto max-w-3xl space-y-8 pb-10">
    @include('igrejas::paineldiretoria.partials.subnav', ['active' => 'list'])

    <div>
        <a href="{{ route($routePrefix.'.show', $church) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-cyan-700 hover:underline dark:text-cyan-400">
            <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
            Voltar à ficha
        </a>
        <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Editar {{ $church->name }}</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Alterações ficam registadas no histórico de auditoria do módulo.</p>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-200">
            <p class="font-semibold">Corrija os campos assinalados.</p>
        </div>
    @endif

    <form action="{{ route($routePrefix.'.update', $church) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
            @include('igrejas::paineldiretoria.churches._form', ['church' => $church])
        </div>
        <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <a href="{{ route($routePrefix.'.show', $church) }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 dark:border-slate-600 dark:text-white dark:hover:bg-slate-700">Cancelar</a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">
                <x-icon name="check" class="h-4 w-4" style="solid" />
                Atualizar
            </button>
        </div>
    </form>

    @can('delete', $church)
        <div class="rounded-2xl border border-red-200/80 bg-red-50/50 p-6 dark:border-red-900/40 dark:bg-red-950/20">
            <h2 class="text-sm font-bold text-red-900 dark:text-red-200">Zona de risco</h2>
            <p class="mt-1 text-xs text-red-800/90 dark:text-red-200/80">Remoção é reversível (soft delete) mas afeta vínculos — confirme com a presidência.</p>
            <form action="{{ route($routePrefix.'.destroy', $church) }}" method="POST" onsubmit="return confirm('Remover esta congregação do cadastro?');" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-red-300 bg-white px-4 py-2.5 text-sm font-bold text-red-700 transition hover:bg-red-50 dark:border-red-800 dark:bg-slate-900 dark:text-red-300 dark:hover:bg-red-950/40">
                    <x-icon name="trash" class="h-4 w-4" style="duotone" />
                    Eliminar congregação
                </button>
            </form>
        </div>
    @endcan
</div>
@endsection
