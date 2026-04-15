{{--
    Navegação interna do chat (Painel Diretoria).
    @var string $active sessions|realtime|statistics
--}}
@php
    $active = $active ?? 'sessions';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25 ring-1 ring-emerald-500/30';
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções do chat">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('diretoria.chat.index') }}" class="{{ $linkBase }} {{ $active === 'sessions' ? $linkActive : $linkIdle }}">
            <x-icon name="list" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Sessões
        </a>
        <a href="{{ route('diretoria.chat.realtime') }}" class="{{ $linkBase }} {{ $active === 'realtime' ? $linkActive : $linkIdle }}">
            <x-icon name="bolt" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Tempo real
        </a>
        @if(Route::has('diretoria.chat.statistics'))
            <a href="{{ route('diretoria.chat.statistics') }}" class="{{ $linkBase }} {{ $active === 'statistics' ? $linkActive : $linkIdle }}">
                <x-icon name="chart-line" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Estatísticas
            </a>
        @endif
    </div>
</nav>
