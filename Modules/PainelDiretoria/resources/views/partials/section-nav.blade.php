@php
    $active = $active ?? '';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25 ring-1 ring-indigo-500/30';

    $u = auth()->user();
    $isExecutive = $u && function_exists('user_is_diretoria_executive') ? user_is_diretoria_executive() : false;
@endphp

<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Navegação interna da diretoria">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('diretoria.dashboard') }}" class="{{ $linkBase }} {{ $active === 'dashboard' ? $linkActive : $linkIdle }}">
            <x-icon name="house" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Painel
        </a>

        <a href="{{ route('diretoria.profile') }}" class="{{ $linkBase }} {{ $active === 'profile' ? $linkActive : $linkIdle }}">
            <x-icon name="user-gear" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Perfil
        </a>

        @if(Route::has('diretoria.notificacoes.index'))
            <a href="{{ route('diretoria.notificacoes.index') }}" class="{{ $linkBase }} {{ $active === 'notificacoes' ? $linkActive : $linkIdle }}">
                <x-icon name="bell" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Notificações
            </a>
        @endif

        @if(Route::has('diretoria.carousel.index') && $isExecutive)
            <a href="{{ route('diretoria.carousel.index') }}" class="{{ $linkBase }} {{ $active === 'carousel' ? $linkActive : $linkIdle }}">
                <x-icon name="images" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Carousel
            </a>
        @endif

        @if(Route::has('diretoria.modules.index') && $isExecutive)
            <a href="{{ route('diretoria.modules.index') }}" class="{{ $linkBase }} {{ $active === 'modules' ? $linkActive : $linkIdle }}">
                <x-icon name="cubes-stacked" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Módulos
            </a>
        @endif

        @if(Route::has('diretoria.homepage.index') && $isExecutive)
            <a href="{{ route('diretoria.homepage.index') }}" class="{{ $linkBase }} {{ $active === 'homepage' ? $linkActive : $linkIdle }}">
                <x-module-icon module="homepage" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Homepage
            </a>
        @endif

        @if(Route::has('admin.config.index') && (function_exists('user_can_access_admin_panel') ? user_can_access_admin_panel() : false))
            <a href="{{ route('admin.config.index') }}" class="{{ $linkBase }} {{ $active === 'config' ? $linkActive : $linkIdle }}">
                <x-icon name="sliders" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Configurações
            </a>
        @endif
    </div>
</nav>
