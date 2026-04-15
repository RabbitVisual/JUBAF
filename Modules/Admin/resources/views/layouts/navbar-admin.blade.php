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
            <!-- Notificações Dropdown - Flowbite -->
            @if(Route::has('admin.notificacoes.index'))
            @php
                $unreadCount = \Illuminate\Support\Facades\DB::table('notifications')
                    ->where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->count();

                $recentNotifications = \Illuminate\Support\Facades\DB::table('notifications')
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            @endphp
            <button type="button" id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotification" class="relative inline-flex items-center justify-center p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-slate-700 focus:ring-4 focus:ring-gray-200 dark:focus:ring-slate-600 transition-colors" aria-label="Notificações">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                @if($unreadCount > 0)
                <span class="absolute top-0.5 right-0.5 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
                <span class="sr-only">Notificações</span>
            </button>

            <!-- Dropdown Notificações -->
            <div id="dropdownNotification" class="z-50 hidden max-w-sm my-4 overflow-hidden text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-lg dark:bg-slate-800 dark:divide-slate-700" data-popper-placement="bottom-end" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 60px);">
                <div class="block px-4 py-2 text-base font-semibold text-center text-gray-700 bg-gray-50 dark:bg-slate-700 dark:text-white">
                    <div class="flex items-center justify-between">
                        <span>Notificações</span>
                        @if($unreadCount > 0)
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">{{ $unreadCount }} não lida{{ $unreadCount > 1 ? 's' : '' }}</span>
                        @endif
                    </div>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    @forelse($recentNotifications as $notification)
                    <a href="{{ $notification->action_url ?? route('admin.notificacoes.index') }}" class="flex px-4 py-3 border-b border-gray-200 hover:bg-gray-100 dark:border-slate-700 dark:hover:bg-slate-700 {{ !$notification->is_read ? 'bg-blue-50 dark:bg-blue-900/10' : '' }}">
                        <div class="flex-shrink-0">
                            @php
                                $iconColors = [
                                    'info' => 'text-blue-500 dark:text-blue-400',
                                    'success' => 'text-emerald-500 dark:text-emerald-400',
                                    'warning' => 'text-amber-500 dark:text-amber-400',
                                    'error' => 'text-red-500 dark:text-red-400',
                                    'system' => 'text-slate-500 dark:text-slate-400',
                                ];
                                $iconColor = $iconColors[$notification->type] ?? 'text-slate-500 dark:text-slate-400';
                            @endphp
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center {{ $iconColor }}">
                                @if($notification->type === 'success')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @elseif($notification->type === 'warning')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                @elseif($notification->type === 'error')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="w-full pl-3">
                            <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                <div class="text-base font-semibold text-gray-900 dark:text-white">{{ $notification->title }}</div>
                                <div class="text-sm font-normal text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($notification->message, 60) }}</div>
                                <span class="text-xs font-medium text-blue-600 dark:text-blue-400">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                            </div>
                        </div>
                        @if(!$notification->is_read)
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center w-2 h-2 p-1 text-xs font-medium leading-none text-white bg-blue-600 rounded-full"></span>
                        </div>
                        @endif
                    </a>
                    @empty
                    <div class="px-4 py-8 text-center">
                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma notificação</p>
                    </div>
                    @endforelse
                </div>
                <a href="{{ route('admin.notificacoes.index') }}" class="block py-2 text-sm font-medium text-center text-gray-900 bg-gray-50 hover:bg-gray-100 dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-white">
                    <div class="inline-flex items-center gap-2">
                        <span>Ver todas as notificações</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </a>
            </div>
            @endif

            <!-- Theme Toggle -->
            <button type="button" id="darkModeToggle" onclick="toggleTheme()" class="inline-flex items-center justify-center p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-slate-700 focus:ring-4 focus:ring-gray-200 dark:focus:ring-slate-600 transition-colors" aria-label="Alternar tema">
                <span id="theme-icon-sun" class="transition-all duration-300">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773l-1.591-1.591M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                </span>
                <span id="theme-icon-moon" class="transition-all duration-300 hidden">
                    <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </span>
            </button>

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





