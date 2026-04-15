{{--
    Navegação interna Avisos (Painel Diretoria) — alinhada ao padrão visual Igrejas (cyan).
    @var string $active list|form
--}}
@php
    $active = $active ?? 'list';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-cyan-600 text-white shadow-md shadow-cyan-600/25 ring-1 ring-cyan-500/30';
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções de avisos JUBAF">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('diretoria.avisos.index') }}" class="{{ $linkBase }} {{ $active === 'list' ? $linkActive : $linkIdle }}">
            <x-icon name="list" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Lista de avisos
        </a>
        @can('create', \Modules\Avisos\App\Models\Aviso::class)
            <a href="{{ route('diretoria.avisos.create') }}" class="{{ $linkBase }} {{ $active === 'form' ? $linkActive : $linkIdle }}">
                <x-icon name="plus" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Novo aviso
            </a>
        @endcan
    </div>
</nav>
