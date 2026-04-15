<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @if (\Illuminate\Support\Facades\Route::has('admin.blog.destroy'))
        <meta name="blog-post-destroy-url-template" content="{{ str_replace('888888888', '__ID__', route('admin.blog.destroy', ['blog' => 888888888])) }}">
    @endif
    <title>@yield('title', 'Painel Admin') — {{ \App\Support\SiteBranding::siteName() }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Inicialização do tema ANTES do CSS para evitar FOUC -->
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('theme') || 'light';
                const html = document.documentElement;

                // Aplicar tema antes de qualquer CSS ser carregado
                if (savedTheme === 'dark') {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
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
    @stack('styles')
</head>
<body class="h-full bg-gray-50 dark:bg-slate-900 transition-colors duration-200 antialiased">
    @if(session()->has('impersonator_id'))
        <!-- Impersonation Banner -->
        <div class="bg-amber-500 text-white py-2 px-4 shadow-md sticky top-0 z-[100] border-b border-amber-600">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-1 bg-white/20 rounded-lg">
                        <x-icon name="eye" class="w-5 h-5" style="duotone" />
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
    <div id="app" class="min-h-full flex flex-col">
        @auth
            @include('admin::layouts.navbar-admin')
            <div class="flex-1 flex overflow-hidden pt-16">
                <!-- Desktop Sidebar - Fixo -->
                <aside id="default-sidebar" class="fixed top-16 left-0 z-30 w-64 h-[calc(100vh-4rem)] transition-transform translate-x-0 hidden lg:block" aria-label="Sidebar">
                    @include('admin::layouts.sidebar-admin')
                </aside>

                <!-- Mobile Sidebar - Flowbite Drawer -->
                <div id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 lg:hidden" tabindex="-1" aria-labelledby="drawer-navigation-label">
                    @include('admin::layouts.sidebar-admin')
                </div>

                <!-- Main Content -->
                <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-slate-900 lg:ml-64">
                    <div class="py-4 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
                        <!-- Flash Messages - Flowbite Alerts -->
                        @if(session('success'))
                            <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800" role="alert">
                                <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3 text-sm font-medium flex-1">
                                    {{ session('success') }}
                                </div>
                                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/30" data-dismiss-target="#alert-success" aria-label="Close">
                                    <span class="sr-only">Fechar</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div id="alert-error" class="flex items-center p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800" role="alert">
                                <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3 text-sm font-medium flex-1">
                                    {{ session('error') }}
                                </div>
                                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30" data-dismiss-target="#alert-error" aria-label="Close">
                                    <span class="sr-only">Fechar</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                </button>
                            </div>
                        @endif

                        @if(session('warning'))
                            <div id="alert-warning" class="flex items-center p-4 mb-4 text-amber-800 border border-amber-300 rounded-lg bg-amber-50 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800" role="alert">
                                <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3 text-sm font-medium flex-1">
                                    {!! session('warning') !!}
                                </div>
                                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-amber-50 text-amber-500 rounded-lg focus:ring-2 focus:ring-amber-400 p-1.5 hover:bg-amber-200 inline-flex h-8 w-8 dark:bg-amber-900/20 dark:text-amber-400 dark:hover:bg-amber-900/30" data-dismiss-target="#alert-warning" aria-label="Close">
                                    <span class="sr-only">Fechar</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                </button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div id="alert-errors" class="flex items-start p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800" role="alert">
                                <svg class="flex-shrink-0 w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3 text-sm font-medium flex-1">
                                    <h3 class="font-semibold mb-2">Erros encontrados:</h3>
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30" data-dismiss-target="#alert-errors" aria-label="Close">
                                    <span class="sr-only">Fechar</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                </button>
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
        // Flowbite Drawer já gerencia o sidebar mobile automaticamente

        // Theme toggle
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');

            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }

            // Atualizar ícones do tema
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
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
        }

        // Atualizar ícones do tema ao carregar
        document.addEventListener('DOMContentLoaded', function() {
            updateThemeIcons();
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

    @stack('scripts')

    <!-- Global Loading Overlay -->
    <x-loading-overlay />
</body>
</html>





