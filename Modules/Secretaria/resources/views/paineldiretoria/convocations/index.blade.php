@extends($layout)

@section('title', 'Convocatórias')

@section('content')
@php
    $inputClass = 'rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20';
    $labelClass = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
    $hasFilter = filled($filters['status'] ?? null);
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'convocatorias'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Secretaria</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
                    <x-icon name="bullhorn" class="h-5 w-5" style="duotone" />
                </span>
                Convocatórias
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Assembleias e antecedência legal.</p>
        </div>
        @can('create', \Modules\Secretaria\App\Models\Convocation::class)
            <a href="{{ route($routePrefix.'.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-900">
                <x-icon name="plus" class="h-4 w-4" style="solid" />
                Nova convocatória
            </a>
        @endcan
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Filtros</h2>
            @if($hasFilter)
                <a href="{{ route($routePrefix.'.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Limpar filtros</a>
            @endif
        </div>
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[12rem]">
                <label class="{{ $labelClass }}">Estado</label>
                <select name="status" class="w-full {{ $inputClass }}">
                    <option value="">Todos</option>
                    @foreach(['draft' => 'Rascunho', 'pending_approval' => 'Pendente', 'approved' => 'Aprovada', 'published' => 'Publicada'] as $k => $l)
                        <option value="{{ $k }}" @selected(($filters['status'] ?? '') == $k)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800 dark:bg-slate-700 dark:hover:bg-slate-600">
                <x-icon name="filter" class="h-4 w-4 opacity-90" style="duotone" />
                Aplicar
            </button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/90 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/90 dark:text-gray-400">
                        <th class="px-5 py-3.5">Título</th>
                        <th class="px-5 py-3.5">Assembleia</th>
                        <th class="px-5 py-3.5">Estado</th>
                        <th class="px-5 py-3.5 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($convocations as $c)
                        <tr class="transition hover:bg-gray-50/80 dark:hover:bg-slate-900/50">
                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $c->title }}</td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-300">{{ $c->assembly_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-300">{{ $c->status }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route($routePrefix.'.show', $c) }}" class="font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">Nenhuma convocatória encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="pt-2">{{ $convocations->links() }}</div>
</div>
@endsection
