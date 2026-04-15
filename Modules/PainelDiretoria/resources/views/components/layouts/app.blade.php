<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (\Illuminate\Support\Facades\Route::has('diretoria.blog.destroy'))
        <meta name="blog-post-destroy-url-template" content="{{ str_replace('888888888', '__ID__', route('diretoria.blog.destroy', ['blog' => 888888888])) }}">
    @endif
    <title>@yield('title', 'JUBAF') — Painel da Diretoria</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Inicialização do tema ANTES do CSS para evitar FOUC -->
    <!-- Seguindo documentação oficial Tailwind CSS v4.1: https://tailwindcss.com/docs/dark-mode -->
    <script>
        (function() {
            'use strict';
            try {
                const savedTheme = localStorage.getItem('theme') || 'light';
                const html = document.documentElement;

                // Aplicar tema antes de qualquer CSS ser carregado
                if (savedTheme === 'dark') {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }

                // Função toggleTheme global (fallback até dark-mode.js carregar)
                // Esta função será sobrescrita pelo dark-mode.js quando carregar
                window.toggleTheme = function() {
                    const html = document.documentElement;
                    const isDark = html.classList.contains('dark');
                    const newTheme = isDark ? 'light' : 'dark';

                    // Salvar preferência
                    try {
                        localStorage.setItem('theme', newTheme);
                    } catch (e) {
                        // localStorage não disponível
                    }

                    // Aplicar/remover classe dark no elemento <html>
                    // Tailwind CSS v4.1 usa @custom-variant dark (&:where(.dark, .dark *))
                    if (newTheme === 'dark') {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }

                    // Forçar reflow para garantir que o navegador aplique as mudanças CSS imediatamente
                    void html.offsetHeight;

                    // Atualizar ícones (aguardar DOM estar disponível)
                    function updateIcons() {
                        const themeIconSun = document.getElementById('theme-icon-sun');
                        const themeIconMoon = document.getElementById('theme-icon-moon');

                        if (themeIconSun && themeIconMoon) {
                            if (newTheme === 'dark') {
                                themeIconSun.classList.add('hidden');
                                themeIconMoon.classList.remove('hidden');
                            } else {
                                themeIconSun.classList.remove('hidden');
                                themeIconMoon.classList.add('hidden');
                            }
                        }
                    }

                    // Tentar atualizar imediatamente
                    updateIcons();

                    // Se DOM não estiver pronto, aguardar
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', updateIcons);
                    }
                };
            } catch (e) {
                // Fallback silencioso se localStorage não estiver disponível
                console.warn('Theme initialization failed:', e);
            }
        })();
    </script>

    <script>
        // Broadcasting config for frontend (notifications/chat runtime)
        window.BROADCAST_DRIVER = @json(config('broadcasting.default', 'log'));
        window.PUSHER_APP_KEY = @json(config('broadcasting.connections.pusher.key'));
        window.PUSHER_APP_CLUSTER = @json(config('broadcasting.connections.pusher.options.cluster', 'mt1'));
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/blog-admin.js'])

    {{-- Stack para estilos adicionais de módulos --}}
    @stack('styles')
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 antialiased">
    @if(session()->has('impersonator_id'))
        <!-- Impersonation Banner -->
        <div class="bg-amber-500 text-white py-2 px-4 shadow-md sticky top-0 z-[100] border-b border-amber-600">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-1 bg-white/20 rounded-lg">
                        <x-icon name="eye" class="w-5 h-5" />
                    </div>
                    <div class="text-sm font-bold tracking-tight">
                        MODO VISUALIZAÇÃO: <span class="font-black uppercase">{{ Auth::user()->name }}</span>
                    </div>
                </div>
                <a href="{{ route('admin.stop-impersonation') }}" class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-black bg-white text-amber-600 rounded-lg hover:bg-amber-50 transition-all shadow-sm transform hover:scale-105 active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                    VOLTAR AO ADMIN
                </a>
            </div>
        </div>
    @endif
    <div id="app" class="h-full flex flex-col overflow-hidden">
        @auth
            @include('paineldiretoria::components.layouts.navbar')
            <div class="flex-1 flex overflow-hidden">
                <!-- Desktop Sidebar -->
                <aside id="sidebar" class="hidden lg:flex lg:flex-shrink-0 lg:w-72">
                    <div class="flex flex-col h-full w-full">
                        @include('paineldiretoria::components.layouts.sidebar')
                    </div>
                </aside>

                <!-- Mobile Sidebar Overlay -->
                <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300" onclick="toggleSidebar()"></div>

                <!-- Mobile Sidebar -->
                <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-800 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden shadow-2xl">
                    <div class="flex flex-col h-full">
                        @include('paineldiretoria::components.layouts.sidebar')
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                    <div class="py-4 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
                        <!-- Flash Messages -->
                        @if(session('success'))
                            <div class="mb-4 md:mb-6 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-4 shadow-sm animate-in slide-in-from-top">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 md:mb-6 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4 shadow-sm animate-in slide-in-from-top">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if(session('warning'))
                            <div class="mb-4 md:mb-6 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-4 shadow-sm animate-in slide-in-from-top">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">{!! session('warning') !!}</p>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-4 md:mb-6 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4 shadow-sm">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-semibold text-red-800 dark:text-red-200 mb-2">Erros encontrados:</h3>
                                        <ul class="space-y-1 text-sm text-red-700 dark:text-red-300">
                                            @foreach($errors->all() as $error)
                                                <li class="flex items-start gap-2">
                                                    <span class="text-red-500 mt-0.5">•</span>
                                                    <span>{{ $error }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>
            </div>
        @else
            @yield('content')
        @endauth
    </div>

    <script>
        // Sidebar toggle function
        function toggleSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar && overlay) {
                const isHidden = sidebar.classList.contains('-translate-x-full');
                if (isHidden) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }
        }

        // Theme toggle - A função toggleTheme() é definida em resources/js/dark-mode.js
        // O dark-mode.js é importado via app.js e gerencia todo o sistema de dark mode
        // Esta função local apenas escuta mudanças de tema para atualizar ícones se necessário
        document.addEventListener('DOMContentLoaded', function() {
            // Escutar mudanças de tema do dark-mode.js
            window.addEventListener('themeChanged', function(e) {
                const isDark = e.detail.theme === 'dark';
                const sunIcon = document.getElementById('theme-icon-sun');
                const moonIcon = document.getElementById('theme-icon-moon');

                if (sunIcon && moonIcon) {
                    if (isDark) {
                        sunIcon.classList.add('hidden');
                        moonIcon.classList.remove('hidden');
                    } else {
                        sunIcon.classList.remove('hidden');
                        moonIcon.classList.add('hidden');
                    }
                }
            });
        });

        // User menu dropdown toggle
        window.toggleUserMenu = function() {
            const menu = document.getElementById('userMenuDropdownMenu');
            if (menu) {
                const isHidden = menu.style.display === 'none' || menu.classList.contains('hidden');
                if (isHidden) {
                    menu.style.display = 'block';
                    menu.classList.remove('hidden');
                } else {
                    menu.style.display = 'none';
                    menu.classList.add('hidden');
                }
            }
        };

        // Fechar menu do usuário ao clicar fora
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userMenuDropdown');
            const menu = document.getElementById('userMenuDropdownMenu');
            if (dropdown && menu && !dropdown.contains(e.target)) {
                menu.style.display = 'none';
                menu.classList.add('hidden');
            }
        });

        // Função para fazer logout com fallback para GET se CSRF expirar
        window.handleLogout = function() {
            const logoutUrl = '{{ route("logout") }}';
            const logoutUrlGet = '{{ route("logout.get") }}';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Tentar fazer logout via POST primeiro
            if (csrfToken) {
                fetch(logoutUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (response.ok || response.redirected) {
                        window.location.href = '{{ route("login") }}';
                    } else if (response.status === 419) {
                        // Token CSRF expirado, usar GET como fallback
                        window.location.href = logoutUrlGet;
                    } else {
                        // Outro erro, tentar GET mesmo assim
                        window.location.href = logoutUrlGet;
                    }
                })
                .catch(error => {
                    console.error('Erro ao fazer logout:', error);
                    // Em caso de erro, usar GET como fallback
                    window.location.href = logoutUrlGet;
                });
            } else {
                // Sem token CSRF, usar GET diretamente
                window.location.href = logoutUrlGet;
            }
        };
    </script>

    @auth
        @if(module_enabled('Chat') && Route::has('diretoria.chat.api.presence') && auth()->user()->hasAnyRole(jubaf_chat_agent_role_names()))
            <script>
                (function () {
                    var url = @json(route('diretoria.chat.api.presence'));
                    var token = document.querySelector('meta[name="csrf-token"]');
                    function ping() {
                        if (!token) return;
                        fetch(url, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': token.getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: '{}'
                        }).catch(function () {});
                    }
                    ping();
                    setInterval(ping, 60000);
                })();
            </script>
        @endif
    @endauth

    @stack('scripts')

    <!-- Global Loading Overlay -->
    <x-loading-overlay />
</body>
</html>
