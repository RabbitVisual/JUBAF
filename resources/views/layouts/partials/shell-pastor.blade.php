<div class="min-h-full flex flex-col">
    <header class="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 py-4 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('pastor.dashboard') }}" class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="flex h-9 w-9 rounded-lg bg-sky-600 text-white items-center justify-center text-sm">P</span>
                <span>Painel Pastor</span>
            </a>
            <nav class="flex flex-wrap items-center gap-2 text-sm font-medium">
                <a href="{{ route('pastor.dashboard') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('pastor.dashboard') ? 'bg-sky-100 text-sky-900 dark:bg-sky-900/40 dark:text-sky-100' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">Início</a>
                @if(module_enabled('Avisos') && Route::has('pastor.avisos.index'))
                    <a href="{{ route('pastor.avisos.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('pastor.avisos.*') ? 'bg-sky-100 text-sky-900 dark:bg-sky-900/40 dark:text-sky-100' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">Avisos</a>
                @endif
                @if(module_enabled('Igrejas') && Route::has('pastor.igrejas.index'))
                    <a href="{{ route('pastor.igrejas.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('pastor.igrejas.*') ? 'bg-sky-100 text-sky-900 dark:bg-sky-900/40 dark:text-sky-100' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">Congregações</a>
                @endif
                <a href="{{ route('homepage') }}" target="_blank" rel="noopener" class="px-3 py-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">Site JUBAF</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 text-sm font-medium">Sair</button>
                </form>
            </nav>
        </div>
    </header>
    <main class="flex-1 max-w-6xl mx-auto px-4 py-8 w-full">
        @include('layouts.partials.flash-messages')
        @yield('content')
    </main>
</div>
