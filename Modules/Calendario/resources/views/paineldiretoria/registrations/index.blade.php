@extends($layout)

@section('title', 'Inscrições em eventos')

@section('content')
@php
    $inputClass = 'rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400';
    $labelClass = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
    $statusLabels = [
        'confirmed' => ['Confirmada', 'bg-emerald-100 text-emerald-900 ring-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-100 dark:ring-emerald-800/50'],
        'waitlist' => ['Lista de espera', 'bg-amber-100 text-amber-950 ring-amber-200 dark:bg-amber-900/40 dark:text-amber-100 dark:ring-amber-800/50'],
        'cancelled' => ['Cancelada', 'bg-gray-100 text-gray-800 ring-gray-200 dark:bg-slate-700 dark:text-gray-200 dark:ring-slate-600'],
    ];
    $hasFilters = filled($filters['status'] ?? null) || filled($filters['event_id'] ?? null);
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('calendario::paineldiretoria.partials.subnav', ['active' => 'registrations'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-sky-700 dark:text-sky-400">Participação</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-600 text-white shadow-lg shadow-sky-600/25">
                    <x-icon name="users" class="h-5 w-5" style="duotone" />
                </span>
                Inscrições
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Todas as inscrições registadas; o check-in continua no ecrã de cada evento.</p>
        </div>
        @can('viewAny', \Modules\Calendario\App\Models\CalendarEvent::class)
            <a href="{{ route('diretoria.calendario.events.index') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-gray-800 shadow-sm transition hover:border-emerald-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                <x-icon name="calendar-days" class="h-4 w-4 text-emerald-600 dark:text-emerald-400" style="duotone" />
                Eventos
            </a>
        @endcan
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Filtros</h2>
            @if($hasFilters)
                <a href="{{ route('diretoria.calendario.registrations.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Limpar filtros</a>
            @endif
        </div>
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[10rem]">
                <label class="{{ $labelClass }}">Estado</label>
                <select name="status" class="w-full {{ $inputClass }}">
                    <option value="">Todos</option>
                    <option value="confirmed" @selected(($filters['status'] ?? '') === 'confirmed')>Confirmada</option>
                    <option value="waitlist" @selected(($filters['status'] ?? '') === 'waitlist')>Lista de espera</option>
                    <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Cancelada</option>
                </select>
            </div>
            <div class="min-w-[14rem] max-w-md grow">
                <label class="{{ $labelClass }}">Evento</label>
                <select name="event_id" class="w-full {{ $inputClass }}">
                    <option value="">Todos</option>
                    @foreach($eventsForFilter as $ev)
                        <option value="{{ $ev->id }}" @selected(($filters['event_id'] ?? '') == $ev->id)>{{ $ev->title }} — {{ $ev->starts_at->format('d/m/Y') }}</option>
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
                        <th class="px-5 py-3.5">Participante</th>
                        <th class="px-5 py-3.5">Evento</th>
                        <th class="px-5 py-3.5">Data do evento</th>
                        <th class="px-5 py-3.5">Estado</th>
                        <th class="px-5 py-3.5">Check-in</th>
                        <th class="px-5 py-3.5 text-right w-32"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($registrations as $r)
                        @php
                            $st = $statusLabels[$r->status] ?? [$r->status, 'bg-gray-100 text-gray-800 ring-gray-200 dark:bg-slate-700 dark:text-gray-200'];
                        @endphp
                        <tr class="transition hover:bg-sky-50/40 dark:hover:bg-slate-900/50">
                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $r->user?->name ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $r->event?->title ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ $r->event?->starts_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-bold ring-1 {{ $st[1] }}">{{ $st[0] }}</span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ $r->checked_in_at?->format('d/m H:i') ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-right">
                                @can('update', $r->event)
                                    <a href="{{ route('diretoria.calendario.events.edit', $r->event) }}" class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Evento</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center text-gray-500 dark:text-gray-400">
                                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 dark:bg-slate-700">
                                    <x-icon name="users" class="h-6 w-6" style="duotone" />
                                </span>
                                <p class="mt-3 font-semibold text-gray-900 dark:text-white">Nenhuma inscrição encontrada</p>
                                <p class="mt-1 text-sm">Ajuste os filtros ou abra as inscrições num evento específico.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="px-1">{{ $registrations->links() }}</div>
</div>
@endsection
