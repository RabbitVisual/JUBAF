@extends($layout)

@section('title', 'Atas')

@section('content')
@php
    $hasFilter = filled($filters['status'] ?? null);
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'atas'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 dark:text-indigo-400">Secretaria</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/25">
                    <x-icon name="file-lines" class="h-5 w-5" style="duotone" />
                </span>
                Atas
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Rascunhos, recolha de assinaturas e publicação.</p>
        </div>
        @can('create', \Modules\Secretaria\App\Models\Minute::class)
            <x-ui.button variant="emerald" size="md" href="{{ route($routePrefix.'.create') }}" class="shrink-0 shadow-md shadow-emerald-600/25">
                <x-icon name="plus" class="h-4 w-4" style="solid" />
                Nova ata
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        <x-slot name="header">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Filtros</h2>
                @if($hasFilter)
                    <a href="{{ route($routePrefix.'.index') }}" class="text-xs font-semibold text-indigo-700 hover:underline dark:text-indigo-400">Limpar filtros</a>
                @endif
            </div>
        </x-slot>

        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[12rem]">
                <x-ui.select label="Estado" name="status">
                    <option value="">Todos</option>
                    @foreach(['draft' => 'Rascunho', 'pending_signatures' => 'Assinaturas pendentes', 'published' => 'Publicada', 'archived' => 'Arquivada'] as $k => $l)
                        <option value="{{ $k }}" @selected(($filters['status'] ?? '') == $k)>{{ $l }}</option>
                    @endforeach
                </x-ui.select>
            </div>
            <x-ui.button type="submit" variant="secondary" size="md" class="!bg-gray-900 !text-white hover:!bg-gray-800 dark:!bg-slate-700 dark:hover:!bg-slate-600">
                <x-icon name="filter" class="h-4 w-4 opacity-90" style="duotone" />
                Aplicar
            </x-ui.button>
        </form>
    </x-ui.card>

    <x-ui.table>
        <x-slot name="head">
            <th class="px-5 py-3.5">Título</th>
            <th class="px-5 py-3.5">Estado</th>
            <th class="px-5 py-3.5 text-right"></th>
        </x-slot>

        @forelse($minutes as $m)
            <tr class="transition odd:bg-white even:bg-gray-50/60 hover:bg-indigo-50/50 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 dark:hover:bg-slate-900/60">
                <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $m->title }}</td>
                <td class="px-5 py-3.5 text-gray-600 dark:text-gray-300">
                    @php
                        $tone = match ($m->status) {
                            'published' => 'success',
                            'pending_signatures' => 'warning',
                            'draft' => 'neutral',
                            'archived' => 'info',
                            default => 'neutral',
                        };
                    @endphp
                    <x-ui.badge :tone="$tone">{{ $m->status }}</x-ui.badge>
                </td>
                <td class="px-5 py-3.5 text-right">
                    <a href="{{ route($routePrefix.'.show', $m) }}" class="font-semibold text-indigo-700 hover:underline dark:text-indigo-400">Ver</a>
                    @can('update', $m)
                        <span class="mx-2 text-gray-300 dark:text-slate-600">|</span>
                        <a href="{{ route($routePrefix.'.edit', $m) }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Editar</a>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">Nenhuma ata encontrada.</td>
            </tr>
        @endforelse
    </x-ui.table>

    <div class="pt-2">{{ $minutes->links() }}</div>
</div>
@endsection
