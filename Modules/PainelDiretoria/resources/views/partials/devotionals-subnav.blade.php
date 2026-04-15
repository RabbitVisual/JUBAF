{{--
    Navegação interna — Devocionais (Painel Diretoria).
    @var string $active lista|nova|edit
--}}
@php
    $active = $active ?? 'lista';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-amber-600 text-white shadow-md shadow-amber-600/25 ring-1 ring-amber-500/30';
    $listaActive = in_array($active, ['lista', 'edit'], true);
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções de devocionais">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('diretoria.devotionals.index') }}" class="{{ $linkBase }} {{ $listaActive ? $linkActive : $linkIdle }}">
            <x-icon name="book-open" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Lista
        </a>
        @can('create', \App\Models\Devotional::class)
            <a href="{{ route('diretoria.devotionals.create') }}" class="{{ $linkBase }} {{ $active === 'nova' ? $linkActive : $linkIdle }}">
                <x-icon name="pen-to-square" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Novo devocional
            </a>
        @endcan
    </div>
</nav>
