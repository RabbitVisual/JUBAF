@extends($layout)

@section('title', 'Arquivo')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'arquivo'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Secretaria</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
                    <x-icon name="folder-open" class="h-5 w-5" style="duotone" />
                </span>
                Arquivo
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Acervo digital com filtros rápidos por categoria e pesquisa.</p>
        </div>
        @can('create', \Modules\Secretaria\App\Models\SecretariaDocument::class)
            <a href="{{ route($routePrefix.'.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-900">
                <x-icon name="upload" class="h-4 w-4" style="solid" />
                Carregar
            </a>
        @endcan
    </div>

    <form method="GET" class="grid gap-3 rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:grid-cols-3">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Pesquisar documento..." class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white">
        <select name="category" class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white">
            <option value="">Todas as categorias</option>
            @foreach(['Estatuto', 'Ofício', 'Circular', 'Outros'] as $category)
                <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-sm font-bold text-white hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600">Filtrar</button>
    </form>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($documents as $d)
            <article class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ $d->title }}</h3>
                    <span class="rounded-lg bg-gray-100 px-2 py-1 text-[11px] font-semibold text-gray-700 dark:bg-slate-700 dark:text-slate-200">{{ $d->category }}</span>
                </div>
                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">{{ $d->is_public ? 'Público' : 'Interno' }}</p>
                <div class="mt-4 flex items-center gap-3 text-sm">
                    @can('download', $d)
                        <a href="{{ route($routePrefix.'.download', $d) }}" class="font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Download</a>
                    @endcan
                    @can('delete', $d)
                        <form action="{{ route($routePrefix.'.destroy', $d) }}" method="POST" class="inline" onsubmit="return confirm('Remover?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="font-semibold text-red-600 hover:underline dark:text-red-400">Eliminar</button>
                        </form>
                    @endcan
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center text-gray-500 dark:border-slate-700 dark:bg-slate-800 dark:text-gray-400">
                Nenhum documento no acervo.
            </div>
        @endforelse
        </div>
    <div class="pt-2">{{ $documents->links() }}</div>
</div>
@endsection
