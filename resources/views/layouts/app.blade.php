<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (\Illuminate\Support\Facades\Route::has('diretoria.blog.destroy'))
        <meta name="blog-post-destroy-url-template" content="{{ str_replace('888888888', '__ID__', route('diretoria.blog.destroy', ['blog' => 888888888])) }}">
    @elseif (\Illuminate\Support\Facades\Route::has('admin.blog.destroy'))
        <meta name="blog-post-destroy-url-template" content="{{ str_replace('888888888', '__ID__', route('admin.blog.destroy', ['blog' => 888888888])) }}">
    @endif

    <title>@yield('title', $erpPageTitleDefault ?? config('app.name', 'JUBAF')) — {{ $erpTitleSuffix ?? '' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @if(!empty($erpIncludePwaMeta))
        <meta name="theme-color" content="{{ ($erpShell ?? '') === 'jovens' ? '#6d28d9' : '#047857' }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="msapplication-tap-highlight" content="no">
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="icon" type="image/svg+xml" href="{{ asset('icons/icon.svg') }}">
        <link rel="apple-touch-icon" href="{{ asset('icons/icon.svg') }}">
        <style>
            body { font-family: 'Inter', system-ui, -apple-system, sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
            .dark ::-webkit-scrollbar-thumb { background: #334155; }
        </style>
    @endif

    <script>
        (function() {
            'use strict';
            try {
                const savedTheme = localStorage.getItem('theme') || 'light';
                const html = document.documentElement;
                if (savedTheme === 'dark') { html.classList.add('dark'); } else { html.classList.remove('dark'); }
                window.toggleTheme = window.toggleTheme || function() {
                    const h = document.documentElement;
                    const isDark = h.classList.contains('dark');
                    const newTheme = isDark ? 'light' : 'dark';
                    try { localStorage.setItem('theme', newTheme); } catch (e) {}
                    if (newTheme === 'dark') { h.classList.add('dark'); } else { h.classList.remove('dark'); }
                    void h.offsetHeight;
                    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: newTheme } }));
                };
            } catch (e) { console.warn('Theme init failed:', e); }
        })();
    </script>

    <script>
        window.BROADCAST_DRIVER = @json(config('broadcasting.default', 'log'));
        window.PUSHER_APP_KEY = @json(config('broadcasting.connections.pusher.key'));
        window.PUSHER_APP_CLUSTER = @json(config('broadcasting.connections.pusher.options.cluster', 'mt1'));
    </script>

    @php
        $viteEntries = ['resources/css/app.css', 'resources/js/app.js'];
        if (!empty($erpIncludeBlogAdminJs)) {
            $viteEntries[] = 'resources/js/blog-admin.js';
        }
    @endphp
    @vite($viteEntries)

    @stack('styles')
</head>
@php
    $shell = $erpShell ?? 'diretoria';
@endphp
<body @class([
    'h-full antialiased overflow-x-hidden',
    'bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 flex flex-col' => in_array($shell, ['lideres', 'jovens'], true),
    'h-full bg-gray-50 dark:bg-slate-900 transition-colors duration-200 antialiased' => $shell === 'admin',
    'h-full bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased' => $shell === 'pastor',
    'h-full bg-gray-50 dark:bg-gray-900 antialiased' => $shell === 'diretoria',
])>
    @include('layouts.partials.impersonation-banner')

    @auth
        @switch($shell)
            @case('admin')
                @include('layouts.partials.shell-admin')
                @break
            @case('pastor')
                @include('layouts.partials.shell-pastor')
                @break
            @case('lideres')
            @case('jovens')
                @include('layouts.partials.shell-mobile-panel')
                @break
            @default
                @include('layouts.partials.shell-diretoria')
        @endswitch
    @else
        <div class="min-h-full">
            @yield('content')
        </div>
    @endauth

    @include('layouts.partials.erp-scripts')

    @stack('scripts')

    <x-loading-overlay />
</body>
</html>
