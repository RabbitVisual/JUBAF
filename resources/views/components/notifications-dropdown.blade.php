@php
    $unreadCount = 0;
    $viewAllRoute = 'notificacoes.index';
    $triggerButtonClass = 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700';
    if (request()->routeIs('admin.*') && Route::has('admin.notificacoes.index')) {
        $viewAllRoute = 'admin.notificacoes.index';
        $triggerButtonClass = 'text-gray-500 dark:text-gray-400 hover:text-gray-900 hover:bg-gray-100 dark:hover:text-white dark:hover:bg-slate-700';
    } elseif (request()->routeIs('diretoria.*') && Route::has('diretoria.notificacoes.index')) {
        $viewAllRoute = 'diretoria.notificacoes.index';
        $triggerButtonClass = 'text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-950/30';
    } elseif (request()->routeIs('jovens.*') && Route::has('jovens.notificacoes.index')) {
        $viewAllRoute = 'jovens.notificacoes.index';
        $triggerButtonClass = 'text-slate-600 dark:text-slate-300 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-950/30';
    } elseif (request()->routeIs('lideres.*') && Route::has('lideres.notificacoes.index')) {
        $viewAllRoute = 'lideres.notificacoes.index';
        $triggerButtonClass = 'text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30';
    }

    if (auth()->check()) {
        try {
            if (class_exists(\Modules\Notificacoes\App\Models\Notificacao::class)) {
                $unreadCount = \Modules\Notificacoes\App\Models\Notificacao::forUser(auth()->id())
                    ->unread()
                    ->count();
            }
        } catch (\Exception $e) {
            $unreadCount = 0;
        }
    }
@endphp

<div class="relative" data-dropdown>
    <button
        data-dropdown-trigger
        type="button"
        class="relative p-2 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $triggerButtonClass }}"
        title="Notificações"
        id="notifications-trigger"
    >
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        @if($unreadCount > 0)
            <span
                id="notifications-badge"
                class="absolute top-0 right-0 h-5 w-5 bg-red-500 rounded-full border-2 border-white dark:border-gray-800 text-xs flex items-center justify-center text-white font-semibold"
            >
                <span id="notifications-count">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            </span>
        @else
            <span id="notifications-badge" class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full border-2 border-white dark:border-gray-800 hidden"></span>
        @endif
    </button>

    <div
        data-dropdown-menu
        class="hidden absolute right-0 mt-2 w-96 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-gray-200 dark:border-slate-700 z-50 max-h-[600px] flex flex-col"
        id="notifications-dropdown"
    >
        <div class="px-4 py-3 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between sticky top-0 bg-white dark:bg-slate-800 z-10">
            <h6 class="font-semibold text-gray-900 dark:text-white">Notificações</h6>
            <div class="flex items-center gap-2">
                <button
                    id="mark-all-read-btn"
                    class="text-xs text-slate-600 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 hidden"
                    title="Marcar todas como lidas"
                >
                    Marcar todas como lidas
                </button>
                <a href="{{ route($viewAllRoute) }}" class="text-xs text-slate-600 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300">
                    Ver todas
                </a>
            </div>
        </div>

        <div class="overflow-y-auto flex-1" id="notifications-list">
            <div class="p-8 text-center" id="notifications-empty">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma notificação</p>
            </div>

            <div id="notifications-loading" class="p-8 text-center hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600"></div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Carregando...</p>
            </div>

            <div id="notifications-items" class="divide-y divide-gray-200 dark:divide-slate-700"></div>
        </div>
    </div>
</div>

