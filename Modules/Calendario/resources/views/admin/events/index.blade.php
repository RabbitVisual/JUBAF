@extends($layout)

@section('title', 'Eventos e prazos')

@section('content')
@php
    $inputClass = 'rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400';
    $labelClass = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
    $visLabels = [
        'publico' => ['Público', 'bg-teal-100 text-teal-900 ring-teal-200 dark:bg-teal-900/40 dark:text-teal-100 dark:ring-teal-800/50'],
        'autenticado' => ['Autenticados', 'bg-slate-100 text-slate-800 ring-slate-200 dark:bg-slate-700 dark:text-slate-100 dark:ring-slate-600'],
        'diretoria' => ['Diretoria', 'bg-emerald-100 text-emerald-900 ring-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-100 dark:ring-emerald-800/50'],
        'lideres' => ['Líderes', 'bg-violet-100 text-violet-900 ring-violet-200 dark:bg-violet-900/40 dark:text-violet-100 dark:ring-violet-800/50'],
        'jovens' => ['Jovens', 'bg-sky-100 text-sky-900 ring-sky-200 dark:bg-sky-900/40 dark:text-sky-100 dark:ring-sky-800/50'],
    ];
    $hasFilters = filled($filters['from'] ?? null) || filled($filters['to'] ?? null) || filled($filters['visibility'] ?? null) || filled($filters['status'] ?? null);
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('calendario::paineldiretoria.partials.subnav', ['active' => 'events'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Agenda completa</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
                    <x-icon name="calendar-days" class="h-5 w-5" style="duotone" />
                </span>
                Eventos
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Filtre por período e visibilidade. Inscrições e check-in gerem-se na edição de cada evento.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('calendario.events.view')
                <a href="{{ route('diretoria.calendario.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700">
                    <x-icon name="chart-pie" class="h-4 w-4 text-emerald-600 dark:text-emerald-400" style="duotone" />
                    Resumo
                </a>
            @endcan
            @can('create', \Modules\Calendario\App\Models\CalendarEvent::class)
                <a href="{{ route('diretoria.calendario.events.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">
                    <x-icon name="plus" class="h-4 w-4" style="solid" />
                    Novo evento
                </a>
            @endcan
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Filtros</h2>
            @if($hasFilters)
                <a href="{{ route('diretoria.calendario.events.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Limpar filtros</a>
            @endif
        </div>
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[9rem]">
                <label class="{{ $labelClass }}">De</label>
                <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="w-full {{ $inputClass }}">
            </div>
            <div class="min-w-[9rem]">
                <label class="{{ $labelClass }}">Até</label>
                <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="w-full {{ $inputClass }}">
            </div>
            <div class="min-w-[12rem]">
                <label class="{{ $labelClass }}">Visibilidade</label>
                <select name="visibility" class="w-full {{ $inputClass }}">
                    <option value="">Todas</option>
                    <option value="publico" @selected(($filters['visibility'] ?? '') === 'publico')>Público</option>
                    <option value="autenticado" @selected(($filters['visibility'] ?? '') === 'autenticado')>Autenticados</option>
                    <option value="diretoria" @selected(($filters['visibility'] ?? '') === 'diretoria')>Diretoria</option>
                    <option value="lideres" @selected(($filters['visibility'] ?? '') === 'lideres')>Líderes</option>
                    <option value="jovens" @selected(($filters['visibility'] ?? '') === 'jovens')>Jovens</option>
                </select>
            </div>
            <div class="min-w-[11rem]">
                <label class="{{ $labelClass }}">Estado</label>
                <select name="status" class="w-full {{ $inputClass }}">
                    <option value="">Todos</option>
                    <option value="published" @selected(($filters['status'] ?? '') === 'published')>Publicado</option>
                    <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Rascunho</option>
                    <option value="waiting_approval" @selected(($filters['status'] ?? '') === 'waiting_approval')>Aguarda aprovação</option>
                    <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Cancelado</option>
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
                        <th class="px-5 py-3.5">Quando</th>
                        <th class="px-5 py-3.5">Título</th>
                        <th class="px-5 py-3.5">Tipo</th>
                        <th class="px-5 py-3.5">Visibilidade</th>
                        <th class="px-5 py-3.5">Estado</th>
                        <th class="px-5 py-3.5 text-center">Inscr.</th>
                        <th class="px-5 py-3.5 text-right w-36"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($events as $e)
                        @php
                            $v = $visLabels[$e->visibility] ?? [$e->visibility, 'bg-gray-100 text-gray-800 ring-gray-200 dark:bg-slate-700 dark:text-gray-200'];
                        @endphp
                        <tr class="transition hover:bg-emerald-50/40 dark:hover:bg-slate-900/50">
                            <td class="whitespace-nowrap px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $e->starts_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $e->title }}</td>
                            <td class="px-5 py-3.5 capitalize text-gray-600 dark:text-gray-400">{{ $e->type }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-bold ring-1 {{ $v[1] }}">{{ $v[0] }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs font-semibold capitalize text-gray-700 dark:text-gray-300">{{ str_replace('_', ' ', $e->status ?? '—') }}</td>
                            <td class="px-5 py-3.5 text-center tabular-nums font-medium text-gray-800 dark:text-gray-200">{{ $e->registrations_count }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    @can('update', $e)
                                        <a href="{{ route('diretoria.calendario.events.edit', $e) }}" class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Editar</a>
                                    @endcan
                                    @can('delete', $e)
                                        <form action="{{ route('diretoria.calendario.events.destroy', $e) }}" method="post" class="inline" onsubmit="return confirm('Remover evento?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:underline dark:text-rose-400">Excluir</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="mx-auto flex max-w-md flex-col items-center">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 dark:bg-slate-700">
                                        <x-icon name="calendar-days" class="h-7 w-7" style="duotone" />
                                    </span>
                                    <p class="mt-4 font-semibold text-gray-900 dark:text-white">Nenhum evento encontrado</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ajuste o período ou crie um novo evento.</p>
                                    @can('create', \Modules\Calendario\App\Models\CalendarEvent::class)
                                        <a href="{{ route('diretoria.calendario.events.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">
                                            <x-icon name="plus" class="h-4 w-4" style="solid" />
                                            Criar evento
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="px-1">{{ $events->links() }}</div>
</div>
@endsection
