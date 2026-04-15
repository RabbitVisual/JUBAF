{{--
    Navegação interna Talentos (Painel Diretoria).
    @var string $active dashboard|directory|assignments
--}}
@php
    $active = $active ?? 'dashboard';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-violet-600 text-white shadow-md shadow-violet-600/25 ring-1 ring-violet-500/30';
    $u = auth()->user();
    $canHub = $u && ($u->can('talentos.directory.view') || $u->can('talentos.assignments.view'));
    $canTaxonomy = $u && $u->can('talentos.taxonomy.manage');
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções de talentos">
    <div class="flex flex-wrap gap-1">
        @if($canHub)
            <a href="{{ route('diretoria.talentos.dashboard') }}" class="{{ $linkBase }} {{ $active === 'dashboard' ? $linkActive : $linkIdle }}">
                <x-icon name="chart-pie" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Resumo
            </a>
        @endif
        @can('talentos.directory.view')
            <a href="{{ route('diretoria.talentos.directory.index') }}" class="{{ $linkBase }} {{ $active === 'directory' ? $linkActive : $linkIdle }}">
                <x-icon name="users" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Diretório
            </a>
        @endcan
        @can('viewAny', \Modules\Talentos\App\Models\TalentAssignment::class)
            <a href="{{ route('diretoria.talentos.assignments.index') }}" class="{{ $linkBase }} {{ $active === 'assignments' ? $linkActive : $linkIdle }}">
                <x-icon name="clipboard-list" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Atribuições
            </a>
        @endcan
        @if($canTaxonomy && Route::has('diretoria.talentos.competencias.index'))
            <a href="{{ route('diretoria.talentos.competencias.index') }}" class="{{ $linkBase }} {{ ($active ?? '') === 'taxonomy' ? $linkActive : $linkIdle }}">
                <x-icon name="sliders" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Competências e áreas
            </a>
        @endif
    </div>
</nav>
