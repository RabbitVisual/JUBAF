<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Scanner de check-in') | {{ \App\Models\Settings::get('site_name', config('app.name', 'Laravel')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('vendor/fontawesome-pro/css/all.css') }}" rel="stylesheet">
</head>

<body class="antialiased bg-gray-950 text-gray-100 min-h-dvh m-0 overflow-x-hidden">
    @yield('content')
    @stack('scripts')
</body>

</html>
