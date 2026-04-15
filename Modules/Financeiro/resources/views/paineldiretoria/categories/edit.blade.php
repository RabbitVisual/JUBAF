@extends($layout)

@section('title', 'Editar categoria')

@section('content')
<div class="mx-auto max-w-3xl space-y-8 pb-10">
    @include('financeiro::paineldiretoria.partials.subnav', ['active' => 'categories'])

    <div>
        <a href="{{ route('diretoria.financeiro.categories.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:gap-2 dark:text-emerald-400">
            <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
            Voltar
        </a>
        <h1 class="mt-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Editar categoria</h1>
        @if($category->is_system)
            <p class="mt-2 text-sm text-amber-800 dark:text-amber-200">Categoria de sistema: o tipo e o código não podem ser alterados (usados pelo Gateway e relatórios).</p>
        @endif
    </div>

    <form action="{{ route('diretoria.financeiro.categories.update', $category) }}" method="post" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="border-b border-gray-100 px-6 py-4 dark:border-slate-700">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Dados</h2>
            </div>
            <div class="p-6">
                @include('financeiro::paineldiretoria.categories._form', ['category' => $category])
            </div>
            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 bg-gray-50/80 px-6 py-4 dark:border-slate-700 dark:bg-slate-900/40">
                <a href="{{ route('diretoria.financeiro.categories.index') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200/80 dark:text-gray-300 dark:hover:bg-slate-700">Cancelar</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700">
                    <x-icon name="check" class="h-4 w-4" style="solid" />
                    Actualizar
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
