@php
    $isJovens = ($erpShell ?? '') === 'jovens';
    $accent = $isJovens ? 'violet' : 'emerald';
    $spinBorder = $isJovens ? 'border-violet-500/20' : 'border-emerald-500/20';
    $spinTop = $isJovens ? 'border-t-violet-500' : 'border-t-emerald-500';
    $spinText = $isJovens ? 'text-violet-600 dark:text-violet-400' : 'text-emerald-600';
    $selection = $isJovens
        ? 'selection:bg-violet-500/20 selection:text-violet-700 dark:selection:text-violet-300'
        : 'selection:bg-emerald-500/20 selection:text-emerald-700';
@endphp
<div id="global-loading" class="fixed inset-0 z-[100] flex items-center justify-center bg-white/80 dark:bg-slate-950/80 backdrop-blur-md transition-opacity duration-300 pointer-events-none opacity-0">
    <div class="flex flex-col items-center">
        <div class="relative w-16 h-16">
            <div class="absolute inset-0 rounded-2xl border-4 {{ $spinBorder }}"></div>
            <div class="absolute inset-0 rounded-2xl border-4 {{ $spinTop }} animate-spin"></div>
        </div>
        <p class="mt-4 text-xs font-semibold {{ $spinText }} animate-pulse">A carregar…</p>
    </div>
</div>

<div class="flex h-full min-h-0 overflow-hidden" x-data="{ sidebarOpen: false }">
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
        @if($isJovens)
            @include('paineljovens::components.layouts.sidebar')
        @else
            @include('painellider::components.layouts.sidebar')
        @endif
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
        @if($isJovens)
            @include('paineljovens::components.layouts.navbar')
        @else
            @include('painellider::components.layouts.navbar')
        @endif

        <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 md:p-6 lg:p-8 space-y-6 md:space-y-8 text-[15px] leading-relaxed text-slate-800 dark:text-slate-200 {{ $selection }}">
            @unless($isJovens && request()->routeIs('jovens.bible.*'))
                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs sm:text-sm font-semibold text-slate-500 dark:text-slate-400 mb-1">
                    <span class="inline-flex items-center gap-1.5 shrink-0">
                        <x-icon name="users" class="w-3.5 h-3.5 {{ $isJovens ? 'text-violet-600 dark:text-violet-400' : 'text-emerald-600 dark:text-emerald-400' }}" />
                        <span class="text-slate-600 dark:text-slate-300">{{ $isJovens ? 'Painel de jovens · Unijovem' : 'Painel de líderes' }}</span>
                    </span>
                    @yield('breadcrumbs')
                </div>
            @endunless

            @if (session('success'))
                <div class="rounded-2xl border px-4 py-3 text-sm font-semibold {{ $isJovens ? 'border-violet-200 dark:border-violet-900/50 bg-violet-50 dark:bg-violet-950/40 text-violet-800 dark:text-violet-200' : 'border-emerald-200 dark:border-emerald-900/50 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-200' }}" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-2xl border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/40 px-4 py-3 text-sm font-semibold text-red-800 dark:text-red-200" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="rounded-2xl border border-amber-200 dark:border-amber-900/50 bg-amber-50 dark:bg-amber-950/40 px-4 py-3 text-sm font-semibold text-amber-900 dark:text-amber-200" role="status">
                    {{ session('warning') }}
                </div>
            @endif

            @if (session('info'))
                <div class="rounded-2xl border border-sky-200 dark:border-sky-900/50 bg-sky-50 dark:bg-sky-950/40 px-4 py-3 text-sm font-semibold text-sky-900 dark:text-sky-200" role="status">
                    {{ session('info') }}
                </div>
            @endif

            @yield('content')
        </main>

        <div class="lg:hidden h-20 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-t border-slate-200 dark:border-slate-800 flex items-center justify-around px-4 pb-safe">
            @if($isJovens)
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
            @else
                <a href="{{ route('lideres.dashboard') }}" class="flex flex-col items-center gap-1 group" aria-label="Início">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ request()->routeIs('lideres.dashboard') ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-400' }}">
                        <x-icon name="grid-2-plus" style="{{ request()->routeIs('lideres.dashboard') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                    </div>
                </a>
                @if(module_enabled('Chat'))
                    <a href="{{ route('lideres.chat.page') }}" class="flex flex-col items-center gap-1 group" aria-label="Chat">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ request()->routeIs('lideres.chat.*') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400' }}">
                            <x-icon name="messages" style="{{ request()->routeIs('lideres.chat.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </div>
                    </a>
                @endif
                <a href="{{ route('lideres.profile.index') }}" class="flex flex-col items-center gap-1 group" aria-label="Perfil">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ request()->routeIs('lideres.profile.*') ? 'bg-slate-800 text-white shadow-lg' : 'text-slate-400' }}">
                        <x-icon name="user-gear" style="{{ request()->routeIs('lideres.profile.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                    </div>
                </a>
            @endif
        </div>
    </div>
</div>

<script>
    window.showLoading = () => {
        const el = document.getElementById('global-loading');
        if (!el) return;
        el.classList.remove('pointer-events-none', 'opacity-0');
        el.classList.add('opacity-100');
    };
    window.hideLoading = () => {
        const el = document.getElementById('global-loading');
        if (!el) return;
        el.classList.add('opacity-0');
        setTimeout(() => el.classList.add('pointer-events-none'), 300);
    };
</script>
