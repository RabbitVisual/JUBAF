{{--
    Navegação interna da Secretaria (Painel Diretoria).
    @var string $active dashboard|reunioes|atas|convocatorias|arquivo
--}}
@php
    $active = $active ?? 'dashboard';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25 ring-1 ring-emerald-500/30';
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções da secretaria">
    <div class="flex flex-wrap gap-1">
        @can('viewAny', \Modules\Secretaria\App\Models\Meeting::class)
            <a href="{{ route('diretoria.secretaria.dashboard') }}" class="{{ $linkBase }} {{ $active === 'dashboard' ? $linkActive : $linkIdle }}">
                <x-icon name="home" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Resumo
            </a>
        @endcan
        @can('viewAny', \Modules\Secretaria\App\Models\Meeting::class)
            <a href="{{ route('diretoria.secretaria.reunioes.index') }}" class="{{ $linkBase }} {{ $active === 'reunioes' ? $linkActive : $linkIdle }}">
                <x-module-icon module="Calendario" class="h-4 w-4 shrink-0" />
                Reuniões
            </a>
        @endcan
        @can('viewAny', \Modules\Secretaria\App\Models\Minute::class)
            <a href="{{ route('diretoria.secretaria.atas.index') }}" class="{{ $linkBase }} {{ $active === 'atas' ? $linkActive : $linkIdle }}">
                <x-module-icon module="Secretaria" class="h-4 w-4 shrink-0" />
                Atas
            </a>
        @endcan
        @can('viewAny', \Modules\Secretaria\App\Models\Convocation::class)
            <a href="{{ route('diretoria.secretaria.convocatorias.index') }}" class="{{ $linkBase }} {{ $active === 'convocatorias' ? $linkActive : $linkIdle }}">
                <x-module-icon module="Avisos" class="h-4 w-4 shrink-0" />
                Convocatórias
            </a>
        @endcan
        @can('viewAny', \Modules\Secretaria\App\Models\SecretariaDocument::class)
            <a href="{{ route('diretoria.secretaria.arquivo.index') }}" class="{{ $linkBase }} {{ $active === 'arquivo' ? $linkActive : $linkIdle }}">
                <x-icon name="folder" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Arquivo
            </a>
        @endcan
    </div>
</nav>
