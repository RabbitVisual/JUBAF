<nav class="sticky top-0 z-30 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center gap-3 h-16 md:h-20">
            <div class="flex items-center gap-3 min-w-0 shrink-0">
                <button type="button" onclick="window.toggleSidebar?.()" class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors" aria-label="Abrir menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <a href="{{ route('diretoria.dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity shrink-0">
                    <img src="{{ \App\Support\SiteBranding::logoDarkUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-9 md:h-11 w-auto max-w-[160px] md:max-w-[200px] object-contain dark:hidden">
                    <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-9 md:h-11 w-auto max-w-[160px] md:max-w-[200px] object-contain hidden dark:block">
                    <div class="hidden sm:block text-left">
                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diretoria</div>
                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ \App\Support\SiteBranding::siteName() }}</div>
                    </div>
                </a>
            </div>

            @auth
                <div class="hidden md:flex flex-1 justify-center min-w-0 max-w-md px-2">
                    @if(\Illuminate\Support\Facades\Route::has('diretoria.dashboard'))
                        <form action="{{ route('diretoria.dashboard') }}" method="get" class="w-full" role="search">
                            <label for="erp-global-search" class="sr-only">Pesquisa no painel</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <x-icon name="search" class="h-4 w-4" style="duotone" />
                                </span>
                                <input id="erp-global-search" type="search" name="q" value="{{ request('q') }}" placeholder="Pesquisar no painel…" title="Redireciona ao dashboard com o termo (extensível)"
                                    class="w-full rounded-xl border border-gray-200 bg-white py-2 pl-9 pr-3 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500">
                            </div>
                        </form>
                    @endif
                </div>
            @endauth

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                @auth
                    <x-notifications-dropdown />

                    @if(user_can_access_admin_panel())
                        <a href="{{ url('/admin') }}" class="hidden lg:inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors" title="Painel Admin">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span class="hidden xl:inline">Admin</span>
                        </a>
                    @endif

                    <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                        <button type="button" @click="open = !open" class="flex items-center gap-2 sm:gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" aria-expanded="false" :aria-expanded="open.toString()">
                            <x-user-avatar :user="Auth::user()" size="sm" class="md:!h-9 md:!w-9 ring-2 ring-gray-200 dark:ring-gray-700" />
                            <div class="hidden sm:block text-left">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[10rem]">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Conta</div>
                            </div>
                            <svg class="hidden sm:block h-4 w-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-0.5"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            @click.outside="open = false"
                            x-cloak
                            class="absolute right-0 mt-2 w-60 rounded-xl border border-gray-200 bg-white py-2 shadow-xl z-50 dark:border-gray-700 dark:bg-gray-800"
                        >
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-3">
                                    <x-user-avatar :user="Auth::user()" size="md" class="!h-10 !w-10 border-2 border-gray-200 dark:border-gray-600" />
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('diretoria.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Meu perfil
                            </a>
                            <button type="button" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" @click="typeof toggleTheme === 'function' && toggleTheme(); open = false">
                                <span id="theme-icon-sun" class="shrink-0 inline-flex">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773l-1.591-1.591M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                                </span>
                                <span id="theme-icon-moon" class="shrink-0 hidden">
                                    <svg class="w-5 h-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                                </span>
                                <span>Alternar tema</span>
                            </button>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-1">
                                <button type="button" onclick="handleLogout()" class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                                    Sair
                                </button>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
