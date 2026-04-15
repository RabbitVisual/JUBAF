{{--
    Navegação interna — Módulos do sistema.
    @var string $active lista|detalhe
--}}
@php
    $active = $active ?? 'lista';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-violet-600 text-white shadow-md shadow-violet-600/25 ring-1 ring-violet-500/30';
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções de módulos">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('diretoria.modules.index') }}" class="{{ $linkBase }} {{ $active === 'lista' || $active === 'detalhe' ? $linkActive : $linkIdle }}">
            <x-icon name="cubes-stacked" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Todos os módulos
        </a>
    </div>
</nav>
