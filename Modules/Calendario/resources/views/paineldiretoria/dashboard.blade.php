@extends($layout)

@section('title', 'Calendário JUBAF')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('calendario::paineldiretoria.partials.subnav', ['active' => 'dashboard'])

    <div class="relative overflow-hidden rounded-3xl border border-blue-200/60 bg-linear-to-br from-white via-blue-50/40 to-slate-50/30 shadow-lg shadow-blue-900/5 dark:border-blue-900/30 dark:from-slate-900 dark:via-blue-950/20 dark:to-slate-900">
        <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-blue-400/15 blur-3xl dark:bg-blue-500/10" aria-hidden="true"></div>
        <div class="pointer-events-none absolute bottom-0 left-1/4 h-40 w-40 rounded-full bg-indigo-400/10 blur-2xl dark:bg-indigo-500/10" aria-hidden="true"></div>
        <div class="relative flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-800 dark:text-blue-400">JUBAF · Agenda institucional</p>
                <div class="mt-3 flex flex-wrap items-start gap-4">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="JUBAF" class="h-12 w-auto shrink-0 dark:brightness-110" width="140" height="44" loading="eager">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                            Centro de eventos
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                            CONJUBAF, assembleias, aniversários de congregações e jovens no mesmo calendário — com inscrições e Gateway alinhados.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex shrink-0 flex-wrap gap-2">
                @can('create', \Modules\Calendario\App\Models\CalendarEvent::class)
                    <a href="{{ route('diretoria.calendario.events.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-900">
                        <x-icon name="plus" class="h-4 w-4" style="solid" />
                        Novo evento
                    </a>
                @endcan
                @can('viewAny', \Modules\Calendario\App\Models\CalendarEvent::class)
                    <a href="{{ route('diretoria.calendario.events.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white/80 px-4 py-2.5 text-sm font-semibold text-gray-800 backdrop-blur-sm transition hover:border-emerald-200 hover:bg-white dark:border-slate-600 dark:bg-slate-800/80 dark:text-white dark:hover:border-emerald-700">
                        <x-icon name="calendar-days" class="h-4 w-4 text-emerald-600 dark:text-emerald-400" style="duotone" />
                        Lista completa
                    </a>
                @endcan
                @can('calendario.registrations.view')
                    <a href="{{ route('diretoria.calendario.registrations.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white/80 px-4 py-2.5 text-sm font-semibold text-gray-800 backdrop-blur-sm transition hover:border-sky-200 dark:border-slate-600 dark:bg-slate-800/80 dark:text-white">
                        <x-icon name="users" class="h-4 w-4 text-sky-600 dark:text-sky-400" style="duotone" />
                        Inscrições
                    </a>
                @endcan
                @if(Route::has('eventos.index'))
                    <a href="{{ route('eventos.index') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white/80 px-4 py-2.5 text-sm font-semibold text-gray-800 backdrop-blur-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800/80 dark:text-white">
                        <x-icon name="globe" class="h-4 w-4 text-gray-500 dark:text-gray-400" style="duotone" />
                        Site público
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="group relative overflow-hidden rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm ring-1 ring-emerald-500/5 transition hover:shadow-md dark:border-emerald-900/40 dark:bg-slate-800">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-emerald-700/80 dark:text-emerald-400/90">Próximos 30 dias</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-emerald-700 dark:text-emerald-300">{{ $upcomingCount }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Eventos agendados no horizonte</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                    <x-icon name="calendar-days" class="h-5 w-5" style="duotone" />
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-sky-100 bg-white p-5 shadow-sm ring-1 ring-sky-500/10 transition hover:shadow-md dark:border-sky-900/40 dark:bg-slate-800">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-sky-800/80 dark:text-sky-400/90">Inscrições abertas</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-sky-700 dark:text-sky-300">{{ $openRegistrationCount }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Eventos futuros com vagas activas</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-200">
                    <x-icon name="clipboard-list" class="h-5 w-5" style="duotone" />
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-violet-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-violet-900/40 dark:bg-slate-800">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-violet-800/80 dark:text-violet-400/90">Inscrições (mês)</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-violet-700 dark:text-violet-300">{{ $registrationsThisMonth }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Novas inscrições não canceladas</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-200">
                    <x-icon name="users" class="h-5 w-5" style="duotone" />
                </div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-amber-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-amber-900/40 dark:bg-slate-800">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-amber-800/80 dark:text-amber-400/90">Pagamentos pendentes</p>
                    <p class="mt-2 text-2xl font-bold tabular-nums text-amber-800 dark:text-amber-200">{{ $pendingPaymentsCount ?? 0 }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Inscrições a aguardar checkout</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200">
                    <x-icon name="credit-card" class="h-5 w-5" style="duotone" />
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="flex flex-col gap-3 border-b border-gray-100 px-2 py-3 dark:border-slate-700 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Calendário mensal</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Eventos, aniversários de jovens e datas de igrejas — tudo junto na mesma vista.</p>
                <p class="mt-2 text-xs leading-relaxed text-gray-600 dark:text-gray-400">
                    <strong class="text-gray-800 dark:text-gray-200">Como usar:</strong> clique num <strong>evento</strong> para abrir a edição. Clique num <strong>dia vazio</strong> para criar um evento com essa data já preenchida (09h–11h).
                </p>
            </div>
            <div class="flex shrink-0 flex-wrap items-center gap-x-4 gap-y-2 text-[11px] font-semibold text-gray-600 dark:text-gray-400">
                <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-[#1d4ed8]" aria-hidden="true"></span> Evento</span>
                <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-[#7c3aed]" aria-hidden="true"></span> Aniversário</span>
                <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-[#0d9488]" aria-hidden="true"></span> Igreja</span>
            </div>
        </div>
        <div id="calendario-diretoria-fc" class="min-h-[480px] p-2"></div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="flex flex-col gap-2 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">
            <div>
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Próximos na agenda</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Primeiros eventos a partir de agora</p>
            </div>
            @can('viewAny', \Modules\Calendario\App\Models\CalendarEvent::class)
                <a href="{{ route('diretoria.calendario.events.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-emerald-700 hover:gap-2 dark:text-emerald-400">
                    Ver todos
                    <x-icon name="arrow-right" class="h-3.5 w-3.5" style="duotone" />
                </a>
            @endcan
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/90 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/90 dark:text-gray-400">
                        <th class="px-5 py-3.5">Quando</th>
                        <th class="px-5 py-3.5">Título</th>
                        <th class="px-5 py-3.5">Inscrições</th>
                        <th class="px-5 py-3.5 w-28"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($nextEvents as $row)
                        <tr class="transition hover:bg-emerald-50/40 dark:hover:bg-slate-900/50">
                            <td class="whitespace-nowrap px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $row->starts_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $row->title }}</td>
                            <td class="px-5 py-3.5 tabular-nums text-gray-600 dark:text-gray-400">{{ $row->registrations_count }}</td>
                            <td class="px-5 py-3.5 text-right">
                                @can('update', $row)
                                    <a href="{{ route('diretoria.calendario.events.edit', $row) }}" class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Editar</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 dark:bg-slate-700 dark:text-gray-500">
                                        <x-icon name="calendar-days" class="h-7 w-7" style="duotone" />
                                    </span>
                                    <p class="mt-4 font-semibold text-gray-900 dark:text-white">Sem eventos futuros</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Crie o próximo encontro ou reunião da JUBAF.</p>
                                    @can('create', \Modules\Calendario\App\Models\CalendarEvent::class)
                                        <a href="{{ route('diretoria.calendario.events.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">
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
</div>

@push('scripts')
@php
    $calCreateEventUrl = route('diretoria.calendario.events.create');
    $calEditEventUrlTemplate = str_replace('999999999', '__ID__', route('diretoria.calendario.events.edit', ['event' => 999999999]));
    $calFeedUrl = $feedUrl ?? route('diretoria.calendario.feed');
@endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/pt.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('calendario-diretoria-fc');
    if (!el || typeof FullCalendar === 'undefined') return;
    const createUrl = @json($calCreateEventUrl);
    const editUrlTemplate = @json($calEditEventUrlTemplate);
    const calendar = new FullCalendar.Calendar(el, {
        locale: 'pt',
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listWeek' },
        navLinks: true,
        dateClick: function (info) {
            const day = info.dateStr.length >= 10 ? info.dateStr.slice(0, 10) : info.dateStr;
            const sep = createUrl.indexOf('?') === -1 ? '?' : '&';
            window.location.href = createUrl + sep + 'date=' + encodeURIComponent(day);
        },
        eventClick: function (info) {
            const p = info.event.extendedProps;
            if (p && p.kind === 'event' && p.eventId) {
                info.jsEvent.preventDefault();
                window.location.href = editUrlTemplate.replace('__ID__', String(p.eventId));
            }
        },
        events: function (info, successCallback, failureCallback) {
            const url = new URL(@json($calFeedUrl));
            url.searchParams.set('start', info.startStr);
            url.searchParams.set('end', info.endStr);
            fetch(url.toString(), { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.json(); })
                .then(function (data) { successCallback(data); })
                .catch(function (e) { failureCallback(e); });
        }
    });
    calendar.render();
});
</script>
@endpush
@endsection
