{{--
    Navegação interna do calendário (Painel Diretoria).
    @var string $active dashboard|events|registrations
--}}
@php
    $active = $active ?? 'dashboard';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25 ring-1 ring-emerald-500/30';
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções do calendário">
    <div class="flex flex-wrap gap-1">
        @can('calendario.events.view')
            <a href="{{ route('diretoria.calendario.dashboard') }}" class="{{ $linkBase }} {{ $active === 'dashboard' ? $linkActive : $linkIdle }}">
                <x-icon name="chart-pie" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Resumo
            </a>
        @endcan
        @can('viewAny', \Modules\Calendario\App\Models\CalendarEvent::class)
            <a href="{{ route('diretoria.calendario.events.index') }}" class="{{ $linkBase }} {{ $active === 'events' ? $linkActive : $linkIdle }}">
                <x-icon name="calendar-days" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Eventos
            </a>
        @endcan
        @can('calendario.registrations.view')
            <a href="{{ route('diretoria.calendario.registrations.index') }}" class="{{ $linkBase }} {{ $active === 'registrations' ? $linkActive : $linkIdle }}">
                <x-icon name="users" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Inscrições
            </a>
        @endcan
    </div>
</nav>
