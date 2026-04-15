<aside class="flex flex-col h-full bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700">
    <style>
        aside::-webkit-scrollbar { width: 6px; }
        aside::-webkit-scrollbar-track { background: rgb(241 245 249); border-radius: 3px; }
        aside::-webkit-scrollbar-thumb { background: rgb(203 213 225); border-radius: 3px; }
        aside::-webkit-scrollbar-thumb:hover { background: rgb(148 163 184); }
        .dark aside::-webkit-scrollbar-track { background: rgb(30 41 59); }
        .dark aside::-webkit-scrollbar-thumb { background: rgb(71 85 105); }
        .dark aside::-webkit-scrollbar-thumb:hover { background: rgb(100 116 139); }
    </style>

    <div class="flex items-center justify-between p-4 lg:p-6 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-md">
                <x-icon name="grid-2" class="w-6 h-6 text-white" style="duotone" />
            </div>
            <div>
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">JUBAF</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Painel admin</p>
            </div>
        </div>
        <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-slate-700 dark:hover:text-gray-300">
            <x-icon name="xmark" class="w-5 h-5" style="duotone" />
            <span class="sr-only">Fechar menu</span>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto px-3 py-4">
        @include('admin::components.sidebar.menu', ['sections' => $adminMenu['sections'] ?? []])
    </div>

    <div class="flex-shrink-0 p-4 border-t border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
        <div class="text-center">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">JUBAF</p>
            <p class="text-xs text-gray-400 dark:text-gray-500">Base de desenvolvimento</p>
        </div>
    </div>
</aside>
