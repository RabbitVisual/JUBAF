<div class="h-full flex flex-col bg-gradient-to-b from-white via-slate-50/80 to-white dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 border-r border-slate-200/90 dark:border-slate-800">
    {{-- Cabeçalho: logo JUBAF + título --}}
    <div class="relative shrink-0 border-b border-slate-200/80 dark:border-slate-800/80 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm">
        <button type="button" @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" aria-label="Fechar menu">
            <x-icon name="xmark" class="w-5 h-5" />
        </button>

        <div class="px-5 pt-6 pb-5 lg:pt-7 lg:pb-6 flex flex-col items-center text-center gap-4">
            <a href="{{ route('lideres.dashboard') }}" class="block focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 rounded-xl">
                <img src="{{ \App\Support\SiteBranding::logoDarkUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-11 sm:h-12 w-auto max-w-[200px] object-contain object-center dark:hidden mx-auto">
                <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-11 sm:h-12 w-auto max-w-[200px] object-contain object-center hidden dark:block mx-auto">
            </a>
            <div class="space-y-1 max-w-[15rem] mx-auto">
                <p class="text-sm sm:text-[15px] font-semibold text-slate-800 dark:text-slate-100 leading-snug tracking-tight">
                    Painel de Líderes JUBAF
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium line-clamp-3">
                    {{ \App\Support\SiteBranding::siteTagline() }}
                </p>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-3 sm:px-4 space-y-6 min-h-0" aria-label="Menu principal">
        <div>
            <p class="px-3 mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                Menu
            </p>
            <ul class="space-y-1.5">
                <li>
                    <a href="{{ route('lideres.dashboard') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-700 dark:hover:text-emerald-300' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.dashboard') ? 'bg-white/15' : 'bg-emerald-100/80 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' }}">
                            <x-icon name="grid-2-plus" style="{{ request()->routeIs('lideres.dashboard') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Início</span>
                    </a>
                </li>
                @if(module_enabled('Bible') && Route::has('lideres.bible.plans.index'))
                <li>
                    <a href="{{ route('lideres.bible.plans.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.bible.*') ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-teal-50 dark:hover:bg-teal-950/40 hover:text-teal-700 dark:hover:text-teal-300' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.bible.*') ? 'bg-white/15' : 'bg-teal-100/80 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400' }}">
                            <x-icon name="book-bible" style="{{ request()->routeIs('lideres.bible.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Bíblia e planos</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Avisos') && Route::has('lideres.avisos.index'))
                <li>
                    <a href="{{ route('lideres.avisos.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.avisos.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 hover:text-indigo-800 dark:hover:text-indigo-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.avisos.*') ? 'bg-white/15' : 'bg-indigo-100/80 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300' }}">
                            <x-module-icon module="Avisos" class="w-5 h-5 {{ request()->routeIs('lideres.avisos.*') ? 'text-white' : 'text-indigo-700 dark:text-indigo-300' }}" style="duotone" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Avisos JUBAF</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Blog') && Route::has('lideres.blog.index'))
                <li>
                    <a href="{{ route('lideres.blog.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.blog.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-800 dark:hover:text-emerald-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.blog.*') ? 'bg-white/15' : 'bg-emerald-100/80 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300' }}">
                            <x-module-icon module="blog" class="w-5 h-5 {{ request()->routeIs('lideres.blog.*') ? 'text-white' : 'text-emerald-700 dark:text-emerald-300' }}" style="duotone" alt="" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Blog JUBAF</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Notificacoes') && Route::has('lideres.notificacoes.index'))
                <li>
                    <a href="{{ route('lideres.notificacoes.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.notificacoes.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-800 dark:hover:text-emerald-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.notificacoes.*') ? 'bg-white/15' : 'bg-emerald-100/80 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300' }}">
                            <x-icon name="bell" style="{{ request()->routeIs('lideres.notificacoes.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Notificações</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Chat'))
                <li>
                    <a href="{{ route('lideres.chat.page') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.chat.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-950/40 hover:text-blue-700 dark:hover:text-blue-300' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.chat.*') ? 'bg-white/15' : 'bg-blue-100/80 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' }}">
                            <x-icon name="messages" style="{{ request()->routeIs('lideres.chat.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Chat</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Calendario') && Route::has('lideres.calendario.index') && auth()->user()?->can('calendario.participate'))
                <li>
                    <a href="{{ route('lideres.calendario.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.calendario.*') ? 'bg-sky-600 text-white shadow-lg shadow-sky-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-sky-50 dark:hover:bg-sky-950/40 hover:text-sky-800 dark:hover:text-sky-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.calendario.*') ? 'bg-white/15' : 'bg-sky-100/80 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300' }}">
                            <x-module-icon module="Calendario" class="w-5 h-5 {{ request()->routeIs('lideres.calendario.*') ? 'text-white' : 'text-sky-700 dark:text-sky-300' }}" style="duotone" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Eventos</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Talentos') && Route::has('lideres.talentos.profile.edit') && auth()->user()?->can('talentos.profile.edit'))
                <li>
                    <a href="{{ route('lideres.talentos.profile.edit') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.talentos.profile.*') ? 'bg-violet-600 text-white shadow-lg shadow-violet-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-950/40 hover:text-violet-800 dark:hover:text-violet-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.talentos.profile.*') ? 'bg-white/15' : 'bg-violet-100/80 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' }}">
                            <x-module-icon module="Talentos" class="w-5 h-5 {{ request()->routeIs('lideres.talentos.profile.*') ? 'text-white' : 'text-violet-700 dark:text-violet-300' }}" style="duotone" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Banco de talentos</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Talentos') && Route::has('lideres.talentos.validation.index') && auth()->user()?->can('paineljovens.talentos.validate'))
                <li>
                    <a href="{{ route('lideres.talentos.validation.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.talentos.validation.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-800 dark:hover:text-emerald-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.talentos.validation.*') ? 'bg-white/15' : 'bg-emerald-100/80 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' }}">
                            <x-icon name="check-double" class="w-5 h-5 {{ request()->routeIs('lideres.talentos.validation.*') ? 'text-white' : 'text-emerald-700 dark:text-emerald-300' }}" style="duotone" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Validar talentos</span>
                    </a>
                </li>
                @endif
                @if(Route::has('lideres.juventude.metrics') && auth()->user()?->can('paineljovens.dashboard.metrics'))
                <li>
                    <a href="{{ route('lideres.juventude.metrics') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.juventude.*') ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-teal-50 dark:hover:bg-teal-950/40 hover:text-teal-800 dark:hover:text-teal-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.juventude.*') ? 'bg-white/15' : 'bg-teal-100/80 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300' }}">
                            <x-icon name="chart-simple" class="w-5 h-5 {{ request()->routeIs('lideres.juventude.*') ? 'text-white' : 'text-teal-700 dark:text-teal-300' }}" style="duotone" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Juventude (métricas)</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('lideres.profile.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.profile.*') ? 'bg-slate-800 text-white shadow-lg dark:bg-slate-700' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.profile.*') ? 'bg-white/15' : 'bg-slate-200/80 dark:bg-slate-700/80 text-slate-700 dark:text-slate-300' }}">
                            <x-icon name="user-gear" style="{{ request()->routeIs('lideres.profile.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Perfil</span>
                    </a>
                </li>
                @if(module_enabled('Igrejas') && Route::has('lideres.congregacao.index'))
                <li>
                    <a href="{{ route('lideres.congregacao.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.congregacao.*') ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-amber-50 dark:hover:bg-amber-950/40 hover:text-amber-800 dark:hover:text-amber-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.congregacao.*') ? 'bg-white/15' : 'bg-amber-100/80 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300' }}">
                            <x-icon name="church" style="{{ request()->routeIs('lideres.congregacao.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Congregação</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Secretaria') && Route::has('lideres.secretaria.index'))
                <li>
                    <a href="{{ route('lideres.secretaria.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('lideres.secretaria.*') ? 'bg-slate-700 text-white dark:bg-slate-600 shadow-lg' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('lideres.secretaria.*') ? 'bg-white/15' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                            <x-icon name="file-lines" style="{{ request()->routeIs('lideres.secretaria.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Secretaria</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('homepage') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400">
                            <x-icon name="arrow-up-right-from-square" style="duotone" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Site público</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
