<div id="app" class="min-h-full flex flex-col">
    @include('admin::layouts.navbar-admin')
    <div class="flex-1 flex overflow-hidden pt-16">
        <aside id="default-sidebar" class="fixed top-16 left-0 z-30 w-64 h-[calc(100vh-4rem)] transition-transform translate-x-0 hidden lg:block bg-gray-50 dark:bg-slate-900 border-r border-gray-200 dark:border-slate-700 overflow-y-auto" aria-label="Sidebar">
            @include('admin::layouts.sidebar-admin')
        </aside>

        <div id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 lg:hidden" tabindex="-1" aria-labelledby="drawer-navigation-label">
            @include('admin::layouts.sidebar-admin')
        </div>

        <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-slate-900 lg:ml-64 min-w-0">
            <div class="py-4 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
                @include('layouts.partials.flash-messages')
                @yield('content')
            </div>
        </main>
    </div>
</div>
