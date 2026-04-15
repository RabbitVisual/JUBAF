<nav class="min-h-16 md:min-h-[4.5rem] lg:min-h-20 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 px-4 md:px-6 lg:px-8 py-3 z-30 sticky top-0">
    <div class="flex items-center gap-3 min-w-0 flex-1">
        <button type="button" @click="sidebarOpen = true" class="lg:hidden shrink-0 w-11 h-11 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-violet-600 hover:text-white transition-all shadow-sm active:scale-95" aria-label="Abrir menu">
            <x-icon name="bars-staggered" style="duotone" class="w-5 h-5" />
        </button>

        <div class="hidden sm:block min-w-0">
            <p class="text-xs font-semibold text-violet-700 dark:text-violet-400 leading-snug truncate max-w-[14rem] md:max-w-none">
                {{ \App\Support\SiteBranding::siteName() }}
            </p>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="w-2 h-2 rounded-full bg-violet-500 shrink-0 animate-pulse shadow-[0_0_8px_theme(colors.violet.500)]" aria-hidden="true"></span>
                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Sessão segura</span>
            </div>
        </div>
    </div>

    <div class="hidden lg:flex flex-1 justify-center max-w-xl px-2 min-w-0">
        <div class="flex items-start gap-3 px-4 py-2.5 bg-slate-100/90 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 w-full">
            <x-icon name="bullhorn" class="w-4 h-4 text-violet-600 dark:text-violet-400 shrink-0 mt-0.5" />
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                Área do jovem cadastrado pelo líder local (Unijovem). Mantém o perfil atualizado e acompanha avisos e mensagens da JUBAF.
            </p>
        </div>
    </div>

    <div class="flex items-center gap-2 md:gap-3 shrink-0">
        @if(module_enabled('Notificacoes') && Route::has('jovens.notificacoes.index'))
            <x-notifications-dropdown />
        @endif

        {{-- Ícones sol/lua: visibilidade via Alpine + classe no <html> (evita falha de dark:hidden em ícones FA duotone) --}}
        <button type="button"
            x-data="{
                isDark: document.documentElement.classList.contains('dark'),
                init() {
                    new MutationObserver(() => {
                        this.isDark = document.documentElement.classList.contains('dark');
                    }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
                }
            }"
            @click="toggleTheme()"
            class="relative w-11 h-11 md:w-12 md:h-12 shrink-0 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-violet-600 dark:hover:text-violet-400 transition-all shadow-sm active:scale-95 group"
            aria-label="Alternar tema">
            <span x-show="!isDark" x-cloak class="absolute inset-0 flex items-center justify-center pointer-events-none" aria-hidden="true">
                <x-icon name="sun-bright" style="duotone" class="w-5 h-5 group-hover:rotate-45 transition-transform" />
            </span>
            <span x-show="isDark" x-cloak class="absolute inset-0 flex items-center justify-center pointer-events-none" aria-hidden="true">
                <x-icon name="moon-stars" style="duotone" class="w-5 h-5 group-hover:-rotate-12 transition-transform" />
            </span>
        </button>

        {{-- @click.away no wrapper: se estiver só no painel, o botão conta como "fora" e fecha no mesmo clique (flicker). --}}
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button type="button" @click="open = !open" class="flex items-center gap-2 md:gap-3 p-1.5 md:pr-3 bg-slate-100 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-violet-500/40 transition-all active:scale-[0.98] group max-w-[220px]">
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-600 flex items-center justify-center text-white text-sm font-bold shadow-md group-hover:rotate-3 transition-transform overflow-hidden shrink-0">
                    @if(user_photo_url(Auth::user()))
                        <img src="{{ user_photo_url(Auth::user()) }}" alt="" class="h-full w-full object-cover">
                    @else
                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                    @endif
                </div>
                <div class="hidden md:flex flex-col text-left min-w-0">
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 truncate">Jovem JUBAF</span>
                </div>
                <x-icon name="chevron-down" class="hidden md:block w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
            </button>

            <div x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 class="absolute right-0 mt-2 w-64 sm:w-72 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl z-50 overflow-hidden py-2">

                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Conta</p>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white break-words">{{ Auth::user()->name }}</p>
                </div>

                <div class="p-2 space-y-0.5">
                    <a href="{{ route('jovens.profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-950/50 hover:text-violet-700 dark:hover:text-violet-300 transition-colors group">
                        <x-icon name="user-vneck" style="duotone" class="w-5 h-5 shrink-0" />
                        <span class="text-sm font-medium">Perfil</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" id="logout-form-jovens">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors group text-left">
                            <x-icon name="power-off" style="duotone" class="w-5 h-5 shrink-0 group-hover:rotate-12 transition-transform" />
                            <span class="text-sm font-medium">Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleTheme() {
        const html = document.documentElement;
        const next = html.classList.contains('dark') ? 'light' : 'dark';
        html.classList.toggle('dark');
        localStorage.setItem('theme', next);
    }
</script>
