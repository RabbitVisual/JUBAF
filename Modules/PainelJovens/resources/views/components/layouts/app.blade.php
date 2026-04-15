<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="theme-color" content="#6d28d9">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="JUBAF Jovens">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="JUBAF — Painel de Jovens">
    <meta name="msapplication-TileColor" content="#6d28d9">
    <meta name="msapplication-tap-highlight" content="no">

    <title>@yield('title', 'Painel de Jovens') — JUBAF</title>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('icons/icon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon.svg') }}">

    <style>
        {{-- Inter/Poppins: resources/css/fonts.css + woff2 em resources/fonts (Vite) --}}
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }

        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-panel {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.toggle('dark', savedTheme === 'dark');
        })();
    </script>

    <script>
        window.BROADCAST_DRIVER = @json(config('broadcasting.default', 'log'));
        window.PUSHER_APP_KEY = @json(config('broadcasting.connections.pusher.key'));
        window.PUSHER_APP_CLUSTER = @json(config('broadcasting.connections.pusher.options.cluster', 'mt1'));
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 flex flex-col selection:bg-violet-500/20 selection:text-violet-700 dark:selection:text-violet-300 overflow-x-hidden" x-data="{ sidebarOpen: false }">

    <div id="global-loading" class="fixed inset-0 z-[100] flex items-center justify-center bg-white/80 dark:bg-slate-950/80 backdrop-blur-md transition-opacity duration-300 pointer-events-none opacity-0">
        <div class="flex flex-col items-center">
            <div class="relative w-16 h-16">
                <div class="absolute inset-0 rounded-2xl border-4 border-violet-500/20"></div>
                <div class="absolute inset-0 rounded-2xl border-4 border-t-violet-500 animate-spin"></div>
            </div>
            <p class="mt-4 text-xs font-semibold text-violet-600 dark:text-violet-400 animate-pulse">A carregar…</p>
        </div>
    </div>

    <div class="flex h-full overflow-hidden">
        <div
            x-show="sidebarOpen"
            class="fixed inset-0 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
        >
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        </div>

        <aside
            class="fixed inset-y-0 left-0 z-50 w-[min(100vw-3rem,20rem)] sm:w-80 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shrink-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            @include('paineljovens::components.layouts.sidebar')
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            @include('paineljovens::components.layouts.navbar')

            <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-6 lg:p-8 space-y-6 md:space-y-8 text-[15px] leading-relaxed text-slate-800 dark:text-slate-200">
                @unless (request()->routeIs('jovens.bible.*'))
                    {{-- Módulo Bíblia: sem breadcrumb no topo (o próprio leitor / hero já contextualiza) --}}
                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs sm:text-sm font-semibold text-slate-500 dark:text-slate-400 mb-1">
                        <span class="inline-flex items-center gap-1.5 shrink-0">
                            <x-icon name="users" class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" />
                            <span class="text-slate-600 dark:text-slate-300">Painel de jovens · Unijovem</span>
                        </span>
                        @yield('breadcrumbs')
                    </div>
                @endunless

                @if (session('success'))
                    <div class="rounded-2xl border border-violet-200 dark:border-violet-900/50 bg-violet-50 dark:bg-violet-950/40 px-4 py-3 text-sm font-semibold text-violet-800 dark:text-violet-200" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <div class="lg:hidden h-20 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-t border-slate-200 dark:border-slate-800 flex items-center justify-around px-4 pb-safe">
                <a href="{{ route('jovens.dashboard') }}" class="flex flex-col items-center gap-1 group" aria-label="Início">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ request()->routeIs('jovens.dashboard') ? 'bg-violet-600 text-white shadow-lg' : 'text-slate-400' }}">
                        <x-icon name="grid-2-plus" style="{{ request()->routeIs('jovens.dashboard') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                    </div>
                </a>
                @if(module_enabled('Chat'))
                <a href="{{ route('jovens.chat.page') }}" class="flex flex-col items-center gap-1 group" aria-label="Chat">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ request()->routeIs('jovens.chat.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400' }}">
                        <x-icon name="messages" style="{{ request()->routeIs('jovens.chat.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                    </div>
                </a>
                @endif
                <a href="{{ route('jovens.profile.index') }}" class="flex flex-col items-center gap-1 group" aria-label="Perfil">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ request()->routeIs('jovens.profile.*') ? 'bg-slate-800 text-white shadow-lg' : 'text-slate-400' }}">
                        <x-icon name="user-gear" style="{{ request()->routeIs('jovens.profile.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                    </div>
                </a>
            </div>
        </div>
    </div>

    @stack('scripts')

    <script>
        window.showLoading = () => {
            const el = document.getElementById('global-loading');
            el.classList.remove('pointer-events-none', 'opacity-0');
            el.classList.add('opacity-100');
        };
        window.hideLoading = () => {
            const el = document.getElementById('global-loading');
            el.classList.add('opacity-0');
            setTimeout(() => el.classList.add('pointer-events-none'), 300);
        };
    </script>
</body>
</html>
