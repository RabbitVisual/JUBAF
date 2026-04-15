<div class="h-full flex flex-col bg-gradient-to-b from-white via-slate-50/80 to-white dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 border-r border-slate-200/90 dark:border-slate-800">
    {{-- Cabeçalho: logo JUBAF + título --}}
    <div class="relative shrink-0 border-b border-slate-200/80 dark:border-slate-800/80 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm">
        <button type="button" @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" aria-label="Fechar menu">
            <x-icon name="xmark" class="w-5 h-5" />
        </button>

        <div class="px-5 pt-6 pb-5 lg:pt-7 lg:pb-6 flex flex-col items-center text-center gap-4">
            <a href="{{ route('jovens.dashboard') }}" class="block focus:outline-none focus-visible:ring-2 focus-visible:ring-violet-500 rounded-xl">
                <img src="{{ \App\Support\SiteBranding::logoDarkUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-11 sm:h-12 w-auto max-w-[200px] object-contain object-center dark:hidden mx-auto">
                <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-11 sm:h-12 w-auto max-w-[200px] object-contain object-center hidden dark:block mx-auto">
            </a>
            <div class="space-y-1 max-w-[15rem] mx-auto">
                <p class="text-sm sm:text-[15px] font-semibold text-slate-800 dark:text-slate-100 leading-snug tracking-tight">
                    Painel de Jovens JUBAF
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
                    <a href="{{ route('jovens.dashboard') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('jovens.dashboard') ? 'bg-violet-600 text-white shadow-lg shadow-violet-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-950/40 hover:text-violet-700 dark:hover:text-violet-300' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('jovens.dashboard') ? 'bg-white/15' : 'bg-violet-100/80 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400' }}">
                            <x-icon name="grid-2-plus" style="{{ request()->routeIs('jovens.dashboard') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Início</span>
                    </a>
                </li>
                @if(Route::has('jovens.devotionals.index'))
                <li>
                    <a href="{{ route('jovens.devotionals.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('jovens.devotionals.*') ? 'bg-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-fuchsia-50 dark:hover:bg-fuchsia-950/40 hover:text-fuchsia-800 dark:hover:text-fuchsia-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('jovens.devotionals.*') ? 'bg-white/15' : 'bg-fuchsia-100/80 dark:bg-fuchsia-900/30 text-fuchsia-700 dark:text-fuchsia-300' }}">
                            <x-icon name="book-open" style="{{ request()->routeIs('jovens.devotionals.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Devocionais</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Bible') && Route::has('jovens.bible.index'))
                @php
                    $bibleActive = request()->routeIs('jovens.bible.*');
                    $bibleSub = 'flex items-center gap-2.5 pl-1 pr-2 py-2 rounded-lg text-sm transition-colors border-l-2 ml-2';
                    $bibleSubActive = 'border-teal-500 bg-teal-50/90 dark:bg-teal-950/50 text-teal-900 dark:text-teal-100 font-semibold';
                    $bibleSubIdle = 'border-transparent text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-teal-800 dark:hover:text-teal-200';
                @endphp
                <li x-data="{ bibleOpen: {{ $bibleActive ? 'true' : 'false' }} }" class="space-y-1">
                    <button type="button" @click="bibleOpen = !bibleOpen" :aria-expanded="bibleOpen"
                        class="group flex w-full items-center gap-3 px-3 py-3 rounded-xl text-left transition-all duration-200 {{ $bibleActive ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-teal-50 dark:hover:bg-teal-950/40 hover:text-teal-700 dark:hover:text-teal-300' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $bibleActive ? 'bg-white/15' : 'bg-teal-100/80 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400' }}">
                            <x-icon name="book-bible" style="{{ $bibleActive ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="flex-1 text-sm font-semibold leading-tight">Bíblia &amp; estudo</span>
                        <x-icon name="chevron-down" class="w-4 h-4 shrink-0 opacity-80 transition-transform duration-200" ::class="bibleOpen ? 'rotate-180' : ''" style="duotone" />
                    </button>
                    <ul x-show="bibleOpen" x-transition class="space-y-0.5 pb-1">
                        <li class="pt-1">
                            <p class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Leitura</p>
                        </li>
                        <li>
                            <a href="{{ route('jovens.bible.index') }}" class="{{ $bibleSub }} {{ request()->routeIs('jovens.bible.index') ? $bibleSubActive : $bibleSubIdle }}">
                                <x-icon name="house" class="w-4 h-4 shrink-0 opacity-80" style="duotone" />
                                <span>Início (versões)</span>
                            </a>
                        </li>
                        @if(Route::has('jovens.bible.read'))
                        <li>
                            <a href="{{ route('jovens.bible.read') }}" class="{{ $bibleSub }} {{ request()->routeIs('jovens.bible.read', 'jovens.bible.book', 'jovens.bible.chapter') ? $bibleSubActive : $bibleSubIdle }}">
                                <x-icon name="book-open" class="w-4 h-4 shrink-0 opacity-80" style="duotone" />
                                <span>Leitor por capítulo</span>
                            </a>
                        </li>
                        @endif
                        @if(Route::has('jovens.bible.interlinear'))
                        <li>
                            <a href="{{ route('jovens.bible.interlinear') }}" class="{{ $bibleSub }} {{ request()->routeIs('jovens.bible.interlinear') ? $bibleSubActive : $bibleSubIdle }}">
                                <x-icon name="layer-group" class="w-4 h-4 shrink-0 opacity-80" style="duotone" />
                                <span>Interlinear</span>
                            </a>
                        </li>
                        @endif
                        @if(Route::has('jovens.bible.favorites'))
                        <li>
                            <a href="{{ route('jovens.bible.favorites') }}" class="{{ $bibleSub }} {{ request()->routeIs('jovens.bible.favorites*') ? $bibleSubActive : $bibleSubIdle }}">
                                <x-icon name="star" class="w-4 h-4 shrink-0 opacity-80" style="duotone" />
                                <span>Favoritos &amp; destaques</span>
                            </a>
                        </li>
                        @endif
                        <li class="pt-2">
                            <p class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Planos de leitura</p>
                        </li>
                        @if(Route::has('jovens.bible.plans.index'))
                        <li>
                            <a href="{{ route('jovens.bible.plans.index') }}" class="{{ $bibleSub }} {{ request()->routeIs('jovens.bible.plans.index', 'jovens.bible.plans.show', 'jovens.bible.plans.preview', 'jovens.bible.reader*', 'jovens.bible.plans.recalculate', 'jovens.bible.plans.pdf') ? $bibleSubActive : $bibleSubIdle }}">
                                <x-icon name="calendar-days" class="w-4 h-4 shrink-0 opacity-80" style="duotone" />
                                <span>Os meus planos</span>
                            </a>
                        </li>
                        @endif
                        @if(Route::has('jovens.bible.plans.catalog'))
                        <li>
                            <a href="{{ route('jovens.bible.plans.catalog') }}" class="{{ $bibleSub }} {{ request()->routeIs('jovens.bible.plans.catalog') ? $bibleSubActive : $bibleSubIdle }}">
                                <x-icon name="book" class="w-4 h-4 shrink-0 opacity-80" style="duotone" />
                                <span>Catálogo de planos</span>
                            </a>
                        </li>
                        @endif
                        @if(Route::has('jovens.bible.search'))
                        <li>
                            <a href="{{ route('jovens.bible.search') }}" class="{{ $bibleSub }} {{ request()->routeIs('jovens.bible.search', 'jovens.bible.api.search') ? $bibleSubActive : $bibleSubIdle }}">
                                <x-icon name="magnifying-glass" class="w-4 h-4 shrink-0 opacity-80" style="duotone" />
                                <span>Busca (planos)</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(module_enabled('Notificacoes') && Route::has('jovens.notificacoes.index'))
                <li>
                    <a href="{{ route('jovens.notificacoes.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('jovens.notificacoes.*') ? 'bg-amber-600 text-white shadow-lg shadow-amber-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-amber-50 dark:hover:bg-amber-950/40 hover:text-amber-800 dark:hover:text-amber-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('jovens.notificacoes.*') ? 'bg-white/15' : 'bg-amber-100/80 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300' }}">
                            <x-icon name="bell" style="{{ request()->routeIs('jovens.notificacoes.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Notificações</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Avisos') && Route::has('jovens.avisos.index'))
                <li>
                    <a href="{{ route('jovens.avisos.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('jovens.avisos.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 hover:text-indigo-800 dark:hover:text-indigo-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('jovens.avisos.*') ? 'bg-white/15' : 'bg-indigo-100/80 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300' }}">
                            <x-module-icon module="Avisos" class="w-5 h-5 {{ request()->routeIs('jovens.avisos.*') ? 'text-white' : 'text-indigo-700 dark:text-indigo-300' }}" style="duotone" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Avisos JUBAF</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Blog') && Route::has('jovens.blog.index'))
                <li>
                    <a href="{{ route('jovens.blog.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('jovens.blog.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-800 dark:hover:text-emerald-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('jovens.blog.*') ? 'bg-white/15' : 'bg-emerald-100/80 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300' }}">
                            <x-module-icon module="blog" class="w-5 h-5 {{ request()->routeIs('jovens.blog.*') ? 'text-white' : 'text-emerald-700 dark:text-emerald-300' }}" style="duotone" alt="" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Blog JUBAF</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Chat'))
                <li>
                    <a href="{{ route('jovens.chat.page') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('jovens.chat.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-950/40 hover:text-blue-700 dark:hover:text-blue-300' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('jovens.chat.*') ? 'bg-white/15' : 'bg-blue-100/80 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' }}">
                            <x-icon name="messages" style="{{ request()->routeIs('jovens.chat.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Chat</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Igrejas') && Route::has('jovens.igreja.index'))
                <li>
                    <a href="{{ route('jovens.igreja.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('jovens.igreja.*') ? 'bg-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-fuchsia-50 dark:hover:bg-fuchsia-950/40 hover:text-fuchsia-800 dark:hover:text-fuchsia-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('jovens.igreja.*') ? 'bg-white/15' : 'bg-fuchsia-100/80 dark:bg-fuchsia-900/30 text-fuchsia-700 dark:text-fuchsia-300' }}">
                            <x-module-icon module="Igrejas" class="w-5 h-5 {{ request()->routeIs('jovens.igreja.*') ? 'text-white' : 'text-fuchsia-700 dark:text-fuchsia-300' }}" style="duotone" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Minha igreja</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Secretaria') && Route::has('jovens.secretaria.index'))
                <li>
                    <a href="{{ route('jovens.secretaria.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('jovens.secretaria.*') ? 'bg-slate-700 text-white shadow-lg' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('jovens.secretaria.*') ? 'bg-white/15' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                            <x-icon name="file-lines" style="{{ request()->routeIs('jovens.secretaria.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Secretaria</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Calendario') && Route::has('jovens.calendario.index') && auth()->user()?->can('calendario.participate'))
                <li>
                    <a href="{{ route('jovens.calendario.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('jovens.calendario.*') ? 'bg-sky-600 text-white shadow-lg shadow-sky-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-sky-50 dark:hover:bg-sky-950/40 hover:text-sky-800 dark:hover:text-sky-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('jovens.calendario.*') ? 'bg-white/15' : 'bg-sky-100/80 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300' }}">
                            <x-module-icon module="Calendario" class="w-5 h-5 {{ request()->routeIs('jovens.calendario.*') ? 'text-white' : 'text-sky-700 dark:text-sky-300' }}" style="duotone" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Eventos</span>
                    </a>
                </li>
                @endif
                @if(module_enabled('Talentos') && Route::has('jovens.talentos.profile.edit') && auth()->user()?->can('talentos.profile.edit'))
                <li>
                    <a href="{{ route('jovens.talentos.profile.edit') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('jovens.talentos.*') ? 'bg-violet-600 text-white shadow-lg shadow-violet-600/25' : 'text-slate-700 dark:text-slate-300 hover:bg-violet-50 dark:hover:bg-violet-950/40 hover:text-violet-800 dark:hover:text-violet-200' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('jovens.talentos.*') ? 'bg-white/15' : 'bg-violet-100/80 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' }}">
                            <x-module-icon module="Talentos" class="w-5 h-5 {{ request()->routeIs('jovens.talentos.*') ? 'text-white' : 'text-violet-700 dark:text-violet-300' }}" style="duotone" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Banco de talentos</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('jovens.profile.index') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('jovens.profile.*') ? 'bg-slate-800 text-white shadow-lg dark:bg-slate-700' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('jovens.profile.*') ? 'bg-white/15' : 'bg-slate-200/80 dark:bg-slate-700/80 text-slate-700 dark:text-slate-300' }}">
                            <x-icon name="user-gear" style="{{ request()->routeIs('jovens.profile.*') ? 'solid' : 'duotone' }}" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Perfil</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('homepage') }}" class="group flex items-center gap-3 px-3 py-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400">
                            <x-icon name="arrow-up-right-from-square" style="duotone" class="w-5 h-5" />
                        </span>
                        <span class="text-sm font-semibold leading-tight text-left">Site público</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
