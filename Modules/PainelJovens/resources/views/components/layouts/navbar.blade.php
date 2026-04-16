@php
    $searchAction = Route::has('jovens.avisos.index') ? route('jovens.avisos.index') : route('jovens.dashboard');
@endphp
<nav class="sticky top-0 z-30 flex min-h-14 flex-col gap-3 border-b border-gray-200 bg-white px-4 py-2.5 dark:border-gray-700 dark:bg-gray-800 md:min-h-16 md:flex-row md:flex-wrap md:items-center md:justify-between md:gap-3 md:px-6 lg:px-8">
    <div class="flex min-w-0 flex-1 items-center gap-3">
        <button type="button" @click="sidebarOpen = true" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-600 transition hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 lg:hidden" aria-label="Abrir menu">
            <x-icon name="bars-staggered" style="duotone" class="h-5 w-5" />
        </button>

        <form method="get" action="{{ $searchAction }}" class="hidden min-w-0 flex-1 md:block lg:max-w-xl" role="search">
            <label for="jovens-nav-search" class="sr-only">Pesquisar avisos</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-icon name="magnifying-glass" class="h-4 w-4 text-gray-500 dark:text-gray-400" style="duotone" />
                </div>
                <input type="search" name="q" id="jovens-nav-search" value="{{ request('q') }}"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-500 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                    placeholder="Pesquisar avisos e comunicados…" autocomplete="off">
            </div>
        </form>

        <div class="flex min-w-0 flex-1 items-center gap-2 md:hidden">
            <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ \App\Support\SiteBranding::siteName() }}</p>
        </div>
    </div>

    <form method="get" action="{{ $searchAction }}" class="w-full basis-full md:hidden" role="search">
        <label for="jovens-nav-search-mobile" class="sr-only">Pesquisar avisos</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <x-icon name="magnifying-glass" class="h-4 w-4 text-gray-500 dark:text-gray-400" style="duotone" />
            </div>
            <input type="search" name="q" id="jovens-nav-search-mobile" value="{{ request('q') }}"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 py-2 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-500"
                placeholder="Pesquisar avisos…" autocomplete="off">
        </div>
    </form>

    <div class="flex w-full shrink-0 items-center justify-end gap-2 md:w-auto md:gap-3">
        @if (module_enabled('Notificacoes') && Route::has('jovens.notificacoes.index'))
            <x-notifications-dropdown />
        @endif

        <button type="button"
            x-data="{
                isDark: document.documentElement.classList.contains('dark'),
                init() {
                    new MutationObserver(() => {
                        this.isDark = document.documentElement.classList.contains('dark');
                    }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
                }
            }"
            @click="window.toggleTheme()"
            class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-600 transition hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
            aria-label="Alternar tema">
            <span x-show="!isDark" x-cloak class="pointer-events-none absolute inset-0 flex items-center justify-center" aria-hidden="true">
                <x-icon name="sun-bright" style="duotone" class="h-5 w-5" />
            </span>
            <span x-show="isDark" x-cloak class="pointer-events-none absolute inset-0 flex items-center justify-center" aria-hidden="true">
                <x-icon name="moon-stars" style="duotone" class="h-5 w-5" />
            </span>
        </button>

        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button type="button" @click="open = !open" class="group flex max-w-[220px] items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 py-1 pl-1 pr-2 transition hover:border-gray-300 hover:bg-white dark:border-gray-600 dark:bg-gray-700 dark:hover:border-gray-500 dark:hover:bg-gray-600 md:pr-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-md bg-blue-600 text-sm font-bold text-white dark:bg-blue-500 md:h-10 md:w-10">
                    @if (user_photo_url(Auth::user()))
                        <img src="{{ user_photo_url(Auth::user()) }}" alt="" class="h-full w-full object-cover">
                    @else
                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                    @endif
                </div>
                <div class="hidden min-w-0 flex-col text-left md:flex">
                    <span class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    <span class="truncate text-xs text-gray-500 dark:text-gray-400">Unijovem</span>
                </div>
                <x-icon name="chevron-down" class="hidden h-3.5 w-3.5 shrink-0 text-gray-400 transition-transform md:block" x-bind:class="open ? 'rotate-180' : ''" />
            </button>

            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="translate-y-1 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                class="absolute right-0 z-50 mt-2 w-60 overflow-hidden rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-600 dark:bg-gray-800 sm:w-64">
                <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-700">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Conta</p>
                    <p class="mt-0.5 break-words text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                </div>
                <div class="p-1">
                    <a href="{{ route('jovens.profile.index') }}" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                        <x-icon name="user-vneck" style="duotone" class="h-5 w-5 shrink-0 text-gray-500" />
                        Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form-jovens">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-md px-3 py-2 text-left text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30">
                            <x-icon name="power-off" style="duotone" class="h-5 w-5 shrink-0" />
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
