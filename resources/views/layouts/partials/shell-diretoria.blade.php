<div id="app" class="h-full flex flex-col overflow-hidden" x-data="{ sidebarNarrow: (() => { try { return localStorage.getItem('erpSidebarNarrow') === '1'; } catch (e) { return false; } })() }" x-init="$watch('sidebarNarrow', v => { try { localStorage.setItem('erpSidebarNarrow', v ? '1' : '0'); } catch (e) {} })">
    @include('layouts.partials.navbar-diretoria')
    <div class="flex-1 flex overflow-hidden min-h-0">
        <aside
            id="sidebar"
            class="hidden lg:flex lg:flex-shrink-0 border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 transition-[width] duration-200 ease-out overflow-hidden"
            :class="sidebarNarrow ? 'w-20' : 'w-72'"
            aria-label="Navegação principal"
        >
            <div class="flex flex-col h-full w-full min-w-0 relative">
                <div class="flex items-center justify-end border-b border-gray-100 dark:border-gray-700/80 px-2 py-1.5 shrink-0">
                    <button
                        type="button"
                        @click="sidebarNarrow = !sidebarNarrow"
                        class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                        :title="sidebarNarrow ? 'Expandir menu' : 'Recolher menu'"
                    >
                        <svg class="h-5 w-5 transition-transform duration-200" :class="sidebarNarrow ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden" :class="sidebarNarrow ? 'px-1.5' : ''">
                    @include('paineldiretoria::components.layouts.sidebar')
                </div>
            </div>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300" onclick="window.toggleSidebar?.()"></div>

        <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-800 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden shadow-2xl border-r border-gray-200 dark:border-gray-700">
            <div class="flex flex-col h-full overflow-y-auto">
                @include('paineldiretoria::components.layouts.sidebar')
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto overflow-x-hidden bg-gray-50 dark:bg-gray-900 min-w-0">
            <div class="py-4 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
                @include('layouts.partials.flash-messages')
                @yield('content')
            </div>
        </main>
    </div>
</div>
