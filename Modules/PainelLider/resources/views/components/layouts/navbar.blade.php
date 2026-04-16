@php
    $liderQuickLinks = $liderQuickLinks ?? [];
    $liderPageEyebrow = $liderPageEyebrow ?? 'Painel de líderes';
    $liderPageTitle = $liderPageTitle ?? 'Painel de líderes';
@endphp

<nav class="sticky top-0 z-30 flex min-h-16 flex-wrap items-center justify-between gap-3 border-b border-slate-200/90 bg-white/85 px-4 py-3 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/85 md:min-h-[4.5rem] md:px-6 lg:min-h-20 lg:px-8">
    <div class="flex min-w-0 flex-1 items-center gap-3">
        <button type="button" @click="sidebarOpen = true" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 shadow-sm transition-all hover:bg-emerald-600 hover:text-white active:scale-95 lg:hidden dark:bg-slate-800 dark:text-slate-300" aria-label="Abrir menu">
            <x-icon name="bars-staggered" style="duotone" class="h-5 w-5" />
        </button>

        <div class="min-w-0 flex-1 sm:max-w-[min(100%,28rem)] lg:max-w-xl">
            <p class="truncate text-[0.65rem] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                {{ $liderPageEyebrow }}
            </p>
            <h1 class="truncate text-base font-bold tracking-tight text-slate-900 dark:text-white md:text-lg">
                {{ $liderPageTitle }}
            </h1>
            <p class="mt-0.5 hidden text-xs font-medium text-slate-500 dark:text-slate-400 sm:line-clamp-1 md:block">
                {{ \App\Support\SiteBranding::siteName() }} — área reservada a líderes de igrejas locais.
            </p>
        </div>
    </div>

    <div class="hidden min-w-0 flex-1 justify-center px-2 md:flex md:max-w-sm lg:max-w-md">
        @if (count($liderQuickLinks) > 0)
            <div class="relative w-full max-w-sm" x-data="{ shortcutsOpen: false }" @click.away="shortcutsOpen = false">
                <button
                    type="button"
                    @click="shortcutsOpen = !shortcutsOpen"
                    :aria-expanded="shortcutsOpen ? 'true' : 'false'"
                    class="flex w-full items-center justify-between gap-2 rounded-2xl border border-slate-200/90 bg-slate-50/90 px-4 py-2.5 text-left text-sm font-semibold text-slate-800 shadow-sm transition-colors hover:border-emerald-500/40 hover:bg-white dark:border-slate-700 dark:bg-slate-950/80 dark:text-slate-100 dark:hover:border-emerald-500/30 dark:hover:bg-slate-900"
                >
                    <span class="flex items-center gap-2 min-w-0">
                        <x-icon name="bolt" style="duotone" class="h-4 w-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
                        <span class="truncate">Atalhos</span>
                    </span>
                    <x-icon name="chevron-down" class="h-3.5 w-3.5 shrink-0 text-slate-400 transition-transform dark:text-slate-500" x-bind:class="shortcutsOpen ? 'rotate-180' : ''" style="duotone" />
                </button>
                <div
                    x-show="shortcutsOpen"
                    x-cloak
                    x-transition
                    class="absolute left-0 right-0 z-50 mt-2 max-h-[min(70vh,22rem)] overflow-y-auto rounded-2xl border border-slate-200 bg-white py-1 shadow-2xl dark:border-slate-700 dark:bg-slate-900"
                >
                    @foreach ($liderQuickLinks as $ql)
                        <a
                            href="{{ $ql['href'] }}"
                            @class([
                                'flex items-center gap-3 px-4 py-2.5 text-sm font-semibold transition-colors',
                                $ql['active']
                                    ? 'bg-emerald-50 text-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-100'
                                    : 'text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800/80',
                            ])
                            @if ($ql['active']) aria-current="page" @endif
                        >
                            <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $ql['active'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }}" aria-hidden="true"></span>
                            {{ $ql['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="flex shrink-0 items-center gap-2 md:gap-3">
        @if (module_enabled('Notificacoes') && Route::has('lideres.notificacoes.index'))
            <x-notifications-dropdown />
        @endif

        <button
            type="button"
            x-data="{
                isDark: document.documentElement.classList.contains('dark'),
                init() {
                    new MutationObserver(() => {
                        this.isDark = document.documentElement.classList.contains('dark');
                    }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
                }
            }"
            @click="window.toggleTheme()"
            class="group relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 shadow-sm transition-all hover:text-emerald-600 active:scale-95 md:h-12 md:w-12 dark:bg-slate-800 dark:text-slate-300 dark:hover:text-emerald-400"
            aria-label="Alternar tema"
        >
            <span x-show="!isDark" x-cloak class="pointer-events-none absolute inset-0 flex items-center justify-center" aria-hidden="true">
                <x-icon name="sun-bright" style="duotone" class="h-5 w-5 transition-transform group-hover:rotate-45" />
            </span>
            <span x-show="isDark" x-cloak class="pointer-events-none absolute inset-0 flex items-center justify-center" aria-hidden="true">
                <x-icon name="moon-stars" style="duotone" class="h-5 w-5 transition-transform group-hover:-rotate-12" />
            </span>
        </button>

        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button type="button" @click="open = !open" class="group flex max-w-[220px] items-center gap-2 rounded-2xl border border-slate-200 bg-slate-100 p-1.5 transition-all hover:border-emerald-500/40 active:scale-[0.98] md:gap-3 md:pr-3 dark:border-slate-700 dark:bg-slate-800">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-sm font-bold text-white shadow-md transition-transform group-hover:rotate-3 md:h-10 md:w-10">
                    @if (user_photo_url(Auth::user()))
                        <img src="{{ user_photo_url(Auth::user()) }}" alt="" class="h-full w-full object-cover">
                    @else
                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                    @endif
                </div>
                <div class="hidden min-w-0 flex-col text-left md:flex">
                    <span class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    <span class="truncate text-xs text-slate-500 dark:text-slate-400">Líder local</span>
                </div>
                <x-icon name="chevron-down" class="hidden h-3.5 w-3.5 shrink-0 text-slate-400 md:block" x-bind:class="open ? 'rotate-180' : ''" />
            </button>

            <div
                x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-2 scale-95 opacity-0"
                x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                class="absolute right-0 z-50 mt-2 w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white py-2 shadow-2xl sm:w-72 dark:border-slate-800 dark:bg-slate-900"
            >
                <div class="border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                    <p class="mb-1 text-xs font-medium text-slate-500 dark:text-slate-400">Conta</p>
                    <p class="break-words text-sm font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                </div>

                <div class="space-y-0.5 p-2">
                    <a href="{{ route('lideres.profile.index') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-700 transition-colors hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-emerald-950/50 dark:hover:text-emerald-300">
                        <x-icon name="user-vneck" style="duotone" class="h-5 w-5 shrink-0" />
                        <span class="text-sm font-medium">Perfil</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" id="logout-form-lideres">
                        @csrf
                        <button type="submit" class="group flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-rose-600 transition-colors hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/40">
                            <x-icon name="power-off" style="duotone" class="h-5 w-5 shrink-0 transition-transform group-hover:rotate-12" />
                            <span class="text-sm font-medium">Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
