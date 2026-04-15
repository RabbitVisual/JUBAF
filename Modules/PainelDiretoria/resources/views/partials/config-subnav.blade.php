{{--
    Navegação contextual — Configurações globais (geralmente admin.config).
    @var string $active settings
--}}
@php
    $active = $active ?? 'settings';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25 ring-1 ring-indigo-500/30';
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Configurações do sistema">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('diretoria.dashboard') }}" class="{{ $linkBase }} {{ $linkIdle }}">
            <x-icon name="house" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Painel da diretoria
        </a>
        @if (Route::has('admin.config.index'))
            <a href="{{ route('admin.config.index') }}" class="{{ $linkBase }} {{ $active === 'settings' ? $linkActive : $linkIdle }}">
                <x-icon name="sliders" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Ajustes globais
            </a>
        @endif
    </div>
</nav>
