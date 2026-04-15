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
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Documentos por visibilidade (diretoria, líderes, público).</p>
        </div>
        @can('create', \Modules\Secretaria\App\Models\SecretariaDocument::class)
            <a href="{{ route($routePrefix.'.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-900">
                <x-icon name="upload" class="h-4 w-4" style="solid" />
                Carregar
            </a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/90 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/90 dark:text-gray-400">
                        <th class="px-5 py-3.5">Título</th>
                        <th class="px-5 py-3.5">Visibilidade</th>
                        <th class="px-5 py-3.5 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($documents as $d)
                        <tr class="transition hover:bg-gray-50/80 dark:hover:bg-slate-900/50">
                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $d->title }}</td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-300">{{ $d->visibility }}</td>
                            <td class="px-5 py-3.5 text-right">
                                @can('download', $d)
                                    <a href="{{ route($routePrefix.'.download', $d) }}" class="font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Download</a>
                                @endcan
                                @can('delete', $d)
                                    @can('download', $d)<span class="mx-2 text-gray-300 dark:text-slate-600">|</span>@endcan
                                    <form action="{{ route($routePrefix.'.destroy', $d) }}" method="POST" class="inline" onsubmit="return confirm('Remover?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="font-semibold text-red-600 hover:underline dark:text-red-400">Eliminar</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">Nenhum documento no arquivo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="pt-2">{{ $documents->links() }}</div>
</div>
@endsection
