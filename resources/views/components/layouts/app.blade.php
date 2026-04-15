<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true'
      }"
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => {
          localStorage.setItem('theme', val ? 'dark' : 'light');
          if (val) {
              document.documentElement.classList.add('dark');
          } else {
              document.documentElement.classList.remove('dark');
          }
      }); $watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'JUBAF') }}</title>

    <!-- Anti-Flicker Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script>
        // Broadcasting config for frontend (notifications/chat runtime)
        window.BROADCAST_DRIVER = @json(config('broadcasting.default', 'log'));
        window.PUSHER_APP_KEY = @json(config('broadcasting.connections.pusher.key'));
        window.PUSHER_APP_CLUSTER = @json(config('broadcasting.connections.pusher.options.cluster', 'mt1'));
    </script>

    <!-- Fonts & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 bg-slate-100 dark:bg-slate-950 antialiased overflow-x-hidden">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content -->
        <main class="flex-1 transition-all duration-300 w-full"
              :class="sidebarCollapsed ? 'ml-20' : 'ml-64'">

            {{ $slot }}

        </main>
    </div>

    <x-loading-overlay />
    <x-toast />

</body>
</html>
