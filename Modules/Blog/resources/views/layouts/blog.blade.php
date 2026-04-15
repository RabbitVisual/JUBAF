<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Blog') — {{ \App\Support\SiteBranding::siteName() }}</title>
    <meta name="description" content="@yield('meta_description', 'Notícias e artigos da JUBAF — Juventude Batista Feirense.')">
    <meta name="keywords" content="@yield('meta_keywords', 'JUBAF, juventude batista, blog, notícias, Feira de Santana')">
    <meta name="author" content="{{ trim($__env->yieldContent('meta_author')) ?: \App\Support\SiteBranding::siteName() }}">

    @php
        $__ogTitle = trim($__env->yieldContent('og_title')) ?: ('Blog — '.\App\Support\SiteBranding::siteName());
        $__ogDesc = trim($__env->yieldContent('og_description')) ?: \App\Support\SiteBranding::siteTagline();
        $__ogImageRaw = trim($__env->yieldContent('og_image')) ?: \App\Support\SiteBranding::logoDefaultUrl();
        $__ogImageAbs = \Illuminate\Support\Str::startsWith($__ogImageRaw, ['http://', 'https://']) ? $__ogImageRaw : url($__ogImageRaw);
        $__twTitle = trim($__env->yieldContent('twitter_title')) ?: $__ogTitle;
        $__twDesc = trim($__env->yieldContent('twitter_description')) ?: $__ogDesc;
        $__twImageRaw = trim($__env->yieldContent('twitter_image')) ?: $__ogImageRaw;
        $__twImageAbs = \Illuminate\Support\Str::startsWith($__twImageRaw, ['http://', 'https://']) ? $__twImageRaw : url($__twImageRaw);
    @endphp

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $__ogTitle }}">
    <meta property="og:description" content="{{ $__ogDesc }}">
    <meta property="og:image" content="{{ $__ogImageAbs }}">
    <meta property="og:site_name" content="{{ \App\Support\SiteBranding::siteName() }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $__twTitle }}">
    <meta name="twitter:description" content="{{ $__twDesc }}">
    <meta name="twitter:image" content="{{ $__twImageAbs }}">

    @stack('article_meta')

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())">

    @stack('json_ld')

    <!-- RSS Feed -->
    <link rel="alternate" type="application/rss+xml" title="Blog {{ \App\Support\SiteBranding::siteName() }}" href="{{ route('blog.rss') }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    {{-- Tema global (mesmo contrato que Homepage): init + toggleTheme antes do Vite; ícones sem conflito display:flex + .hidden --}}
    <script>
        (function() {
            'use strict';
            try {
                const html = document.documentElement;
                const stored = localStorage.getItem('theme');
                if (stored === 'dark') {
                    html.classList.add('dark');
                } else if (stored === 'light') {
                    html.classList.remove('dark');
                } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }

                function updateThemeIconsForCurrentHtml() {
                    const themeIconSun = document.getElementById('theme-icon-sun');
                    const themeIconMoon = document.getElementById('theme-icon-moon');
                    if (!themeIconSun || !themeIconMoon) {
                        return;
                    }
                    const isDark = html.classList.contains('dark');
                    if (isDark) {
                        themeIconSun.classList.add('hidden');
                        themeIconMoon.classList.remove('hidden');
                    } else {
                        themeIconSun.classList.remove('hidden');
                        themeIconMoon.classList.add('hidden');
                    }
                }

                window.toggleTheme = function() {
                    const isDark = html.classList.contains('dark');
                    const newTheme = isDark ? 'light' : 'dark';
                    try {
                        localStorage.setItem('theme', newTheme);
                    } catch (e) {}
                    if (newTheme === 'dark') {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }
                    void html.offsetHeight;
                    updateThemeIconsForCurrentHtml();
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', updateThemeIconsForCurrentHtml);
                } else {
                    updateThemeIconsForCurrentHtml();
                }
            } catch (e) {
                console.warn('Theme initialization failed:', e);
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased">
    <!-- Header: apenas logo (sem repetir nome do site ao lado) -->
    <header class="sticky top-0 z-40 border-b border-blue-100/80 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-[4.25rem]">
                <a href="{{ route('homepage') }}" class="flex items-center shrink-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded-lg" aria-label="{{ \App\Support\SiteBranding::siteName() }} — início">
                    <img src="{{ \App\Support\SiteBranding::logoDarkUrl() }}" alt="" class="h-9 md:h-10 w-auto max-w-[200px] object-contain object-left dark:hidden">
                    <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}" alt="" class="h-9 md:h-10 w-auto max-w-[200px] object-contain object-left hidden dark:block">
                </a>

                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('homepage') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                        Início
                    </a>
                    <div class="flex flex-col items-end">
                        <a href="{{ route('blog.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold text-blue-700 dark:text-blue-400 bg-blue-50/90 dark:bg-blue-950/50">
                            Blog
                        </a>
                        <span class="hidden md:block text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 pr-1">Comunicação da liderança</span>
                    </div>
                </nav>

                <div class="flex items-center gap-2 sm:gap-3">
                    <form action="{{ route('blog.search') }}" method="GET" class="hidden sm:block">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar…"
                                class="w-52 lg:w-64 pl-10 pr-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-icon name="magnifying-glass" class="h-4 w-4 text-slate-400" />
                            </div>
                        </div>
                    </form>

                    <button type="button" id="darkModeToggle" onclick="toggleTheme()" class="relative inline-flex h-10 w-10 shrink-0 items-center justify-center p-2 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" aria-label="Alternar tema">
                        {{-- Mesmo padrão que navbar-homepage: spans absolute, lua só com .hidden (nunca misturar inline-flex/flex com .hidden no mesmo nó — conflito de display no Tailwind). --}}
                        <span id="theme-icon-sun" class="absolute transition-all duration-300">
                            <x-icon name="sun" style="duotone" class="h-5 w-5 text-amber-500" />
                        </span>
                        <span id="theme-icon-moon" class="absolute transition-all duration-300 hidden">
                            <x-icon name="moon" style="duotone" class="h-5 w-5 text-blue-400" />
                        </span>
                    </button>

                    <button type="button" class="md:hidden p-2 text-slate-500 hover:text-slate-900 dark:hover:text-white rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" id="mobile-menu-button" aria-label="Menu">
                        <x-icon name="bars" class="h-6 w-6" />
                    </button>
                </div>
            </div>

            <div class="md:hidden hidden border-t border-slate-100 dark:border-slate-800" id="mobile-menu">
                <div class="py-3 space-y-1">
                    <a href="{{ route('homepage') }}" class="block px-3 py-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800">Início</a>
                    <a href="{{ route('blog.index') }}" class="block px-3 py-2 rounded-lg font-semibold text-blue-700 dark:text-blue-400 bg-blue-50/80 dark:bg-blue-950/40">Blog</a>
                </div>
                <div class="px-3 pb-4">
                    <form action="{{ route('blog.search') }}" method="GET">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar no blog…"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-blue-500">
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <div class="lg:col-span-1">
                    <a href="{{ route('homepage') }}" class="inline-block mb-4 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded-lg" aria-label="{{ \App\Support\SiteBranding::siteName() }}">
                        <img src="{{ \App\Support\SiteBranding::logoDefaultUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-12 md:h-14 w-auto max-w-[220px] object-contain object-left dark:brightness-110">
                    </a>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                        {{ \App\Support\SiteBranding::siteTagline() }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-blue-800 dark:text-blue-300 mb-3">Blog</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                        Comunicação institucional — notícias, eventos e vida das igrejas.
                    </p>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('blog.index') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-700 dark:hover:text-blue-400">Todas as publicações</a></li>
                        <li><a href="{{ route('blog.rss') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-700 dark:hover:text-blue-400">Feed RSS</a></li>
                        <li><a href="{{ route('blog.sitemap') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-700 dark:hover:text-blue-400">Mapa do site (XML)</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-blue-800 dark:text-blue-300 mb-3">Institucional</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('homepage') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-700 dark:hover:text-blue-400">Página inicial</a></li>
                        @if(Route::has('contato'))
                        <li><a href="{{ route('contato') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-700 dark:hover:text-blue-400">Contato</a></li>
                        @endif
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-blue-800 dark:text-blue-300 mb-3">Local</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm">Feira de Santana — Bahia</p>
                </div>
            </div>
            <div class="mt-10 pt-8 border-t border-slate-200 dark:border-slate-800 text-center text-sm text-slate-500 dark:text-slate-500">
                © {{ date('Y') }} {{ \App\Support\SiteBranding::siteName() }}
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
