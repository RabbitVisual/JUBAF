<nav class="fixed top-0 left-0 right-0 z-50 h-16 bg-white border-b border-gray-200 dark:bg-slate-800 dark:border-slate-700 shadow-sm">
    <div class="flex items-center justify-between h-full px-4 sm:px-6 lg:px-8">
        <!-- Left Section: Mobile Menu + Logo -->
        <div class="flex items-center gap-4">
            <!-- Mobile menu button - Flowbite Drawer -->
            <button data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation" aria-controls="drawer-navigation" type="button" class="lg:hidden p-2 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-gray-900 focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-slate-700 dark:hover:text-white dark:focus:ring-slate-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <span class="sr-only">Abrir menu</span>
            </button>

            <!-- Logo and Brand -->
            <a href="{{ url('/admin') }}" class="flex items-center gap-3 text-gray-900 dark:text-white hover:opacity-90 transition-opacity">
                <img src="{{ asset('images/logo-icon.svg') }}" alt="Logo" class="h-8 w-8 md:h-10 md:w-10">
                <div class="hidden sm:block">
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Painel</div>
                    <div class="text-sm md:text-base font-bold text-gray-900 dark:text-white">Administrativo</div>
                </div>
            </a>
        </div>

        <!-- Right Section: Actions -->
        <div class="flex items-center gap-2 sm:gap-4">
            @if(Route::has('admin.notificacoes.index'))
                <x-notifications-dropdown />
            @endif

            @if(Route::has('admin.dashboard'))
                <form action="{{ route('admin.dashboard') }}" method="get" class="hidden md:block flex-1 max-w-xs min-w-0 mx-2" role="search">
                    <label for="admin-erp-search" class="sr-only">Pesquisa</label>
                    <input id="admin-erp-search" type="search" name="q" value="{{ request('q') }}" placeholder="Pesquisar…" class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2 px-3 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                </form>
            @endif

            <!-- User Dropdown - Flowbite -->
            <button type="button" class="flex items-center gap-2 sm:gap-3 text-sm bg-white rounded-full focus:ring-4 focus:ring-gray-200 dark:focus:ring-slate-600 dark:bg-slate-800" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom-end">
                <span class="sr-only">Abrir menu do usuário</span>
                <x-user-avatar :user="auth()->user()" size="sm" class="md:!h-9 md:!w-9 border-2 border-gray-200 dark:border-slate-600" />
                <div class="hidden lg:block text-left">
                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Administrador</div>
                </div>
                <svg class="hidden lg:block w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <!-- Dropdown menu -->
            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg dark:bg-slate-800 dark:divide-slate-700" id="user-dropdown" data-popper-placement="bottom-end" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 60px);">
                <div class="px-4 py-3">
                    <span class="block text-sm text-gray-900 dark:text-white font-semibold">{{ auth()->user()->name }}</span>
                    <span class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ auth()->user()->email }}</span>
                </div>
                <ul class="py-2" aria-labelledby="user-menu-button">
                    <li>
                        <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-slate-700 dark:text-gray-300 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Meu Perfil
                        </a>
                    </li>
                    <li>
                        <button type="button" onclick="typeof toggleTheme === 'function' && toggleTheme()" class="flex w-full items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-slate-700 dark:text-gray-300 dark:hover:text-white">
                            <span id="theme-icon-sun" class="inline-flex shrink-0">
                                <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773l-1.591-1.591M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                            </span>
                            <span id="theme-icon-moon" class="hidden shrink-0">
                                <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                            </span>
                            Alternar tema
                        </button>
                    </li>
                </ul>
                <div class="py-2">
                    <button type="button" onclick="handleLogout()" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:text-red-400 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Sair
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>





