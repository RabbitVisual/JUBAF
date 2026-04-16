@php
    $link = 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors';
    $idle = 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700';
    $active = 'bg-gray-100 text-blue-700 dark:bg-gray-700 dark:text-blue-400';
    $iconBox = 'flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-[15px]';
    $iconIdle = 'bg-gray-100 text-gray-500 dark:bg-gray-600 dark:text-gray-400';
    $iconActive = 'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300';
@endphp

<div class="flex h-full flex-col bg-white dark:bg-gray-800">
    <div class="relative shrink-0 border-b border-gray-200 dark:border-gray-700">
        <button type="button" @click="sidebarOpen = false" class="lg:hidden absolute right-3 top-3 z-10 flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600" aria-label="Fechar menu">
            <x-icon name="xmark" class="h-4 w-4" />
        </button>

        <a href="{{ route('jovens.dashboard') }}" class="flex items-center gap-3 px-4 py-4 lg:px-5 lg:py-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-800">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-gray-900">
                <img src="{{ \App\Support\SiteBranding::logoDarkUrl() }}" alt="" class="h-8 w-auto max-w-[2.25rem] object-contain dark:hidden">
                <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}" alt="" class="hidden h-8 w-auto max-w-[2.25rem] object-contain dark:block">
            </span>
            <span class="min-w-0 flex-1 text-left">
                <span class="block text-sm font-semibold text-gray-900 dark:text-white">Painel Jovens</span>
                <span class="mt-0.5 block truncate text-xs text-gray-500 dark:text-gray-400">{{ \App\Support\SiteBranding::siteName() }}</span>
            </span>
        </a>
    </div>

    <nav class="min-h-0 flex-1 space-y-6 overflow-y-auto px-3 py-4 sm:px-4" aria-label="Menu principal">
        <div>
            <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Menu</p>
            <ul class="space-y-0.5">
                <li>
                    <a href="{{ route('jovens.dashboard') }}" @class([$link, request()->routeIs('jovens.dashboard') ? $active : $idle])>
                        <span @class([$iconBox, request()->routeIs('jovens.dashboard') ? $iconActive : $iconIdle])>
                            <x-icon name="grid-2-plus" style="{{ request()->routeIs('jovens.dashboard') ? 'solid' : 'duotone' }}" class="h-[1.05rem] w-[1.05rem]" />
                        </span>
                        <span>Início</span>
                    </a>
                </li>
                @if (Route::has('jovens.devotionals.index'))
                    <li>
                        <a href="{{ route('jovens.devotionals.index') }}" @class([$link, request()->routeIs('jovens.devotionals.*') ? $active : $idle])>
                            <span @class([$iconBox, request()->routeIs('jovens.devotionals.*') ? $iconActive : $iconIdle])>
                                <x-icon name="book-open" style="{{ request()->routeIs('jovens.devotionals.*') ? 'solid' : 'duotone' }}" class="h-[1.05rem] w-[1.05rem]" />
                            </span>
                            <span>Devocionais</span>
                        </a>
                    </li>
                @endif
                @if (module_enabled('Bible') && Route::has('jovens.bible.index'))
                    @php
                        $bibleActive = request()->routeIs('jovens.bible.*');
                        $bSub = 'ml-2 flex items-center gap-2 rounded-md border-l-2 py-2 pl-3 pr-2 text-sm transition-colors';
                        $bSubOn = 'border-blue-600 bg-gray-50 font-medium text-blue-700 dark:border-blue-500 dark:bg-gray-700/50 dark:text-blue-300';
                        $bSubOff = 'border-transparent text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50';
                    @endphp
                    <li x-data="{ bibleOpen: {{ $bibleActive ? 'true' : 'false' }} }" class="space-y-0.5">
                        <button type="button" @click="bibleOpen = !bibleOpen" :aria-expanded="bibleOpen"
                            @class([$link, 'w-full text-left', $bibleActive ? $active : $idle])>
                            <span @class([$iconBox, $bibleActive ? $iconActive : $iconIdle])>
                                <x-icon name="book-bible" style="{{ $bibleActive ? 'solid' : 'duotone' }}" class="h-[1.05rem] w-[1.05rem]" />
                            </span>
                            <span class="flex-1">Bíblia e estudo</span>
                            <x-icon name="chevron-down" class="h-3.5 w-3.5 shrink-0 text-gray-400 transition-transform duration-200" ::class="bibleOpen ? 'rotate-180' : ''" style="duotone" />
                        </button>
                        <ul x-show="bibleOpen" x-transition class="space-y-0.5 pb-1">
                            <li class="px-1 pt-1">
                                <p class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Leitura</p>
                            </li>
                            <li>
                                <a href="{{ route('jovens.bible.index') }}" class="{{ $bSub }} {{ request()->routeIs('jovens.bible.index') ? $bSubOn : $bSubOff }}">
                                    <x-icon name="house" class="h-3.5 w-3.5 shrink-0 opacity-70" style="duotone" />
                                    <span>Versões</span>
                                </a>
                            </li>
                            @if (Route::has('jovens.bible.read'))
                                <li>
                                    <a href="{{ route('jovens.bible.read') }}" class="{{ $bSub }} {{ request()->routeIs('jovens.bible.read', 'jovens.bible.book', 'jovens.bible.chapter') ? $bSubOn : $bSubOff }}">
                                        <x-icon name="book-open" class="h-3.5 w-3.5 shrink-0 opacity-70" style="duotone" />
                                        <span>Leitor</span>
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('jovens.bible.interlinear'))
                                <li>
                                    <a href="{{ route('jovens.bible.interlinear') }}" class="{{ $bSub }} {{ request()->routeIs('jovens.bible.interlinear') ? $bSubOn : $bSubOff }}">
                                        <x-icon name="layer-group" class="h-3.5 w-3.5 shrink-0 opacity-70" style="duotone" />
                                        <span>Interlinear</span>
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('jovens.bible.favorites'))
                                <li>
                                    <a href="{{ route('jovens.bible.favorites') }}" class="{{ $bSub }} {{ request()->routeIs('jovens.bible.favorites*') ? $bSubOn : $bSubOff }}">
                                        <x-icon name="star" class="h-3.5 w-3.5 shrink-0 opacity-70" style="duotone" />
                                        <span>Favoritos</span>
                                    </a>
                                </li>
                            @endif
                            <li class="px-1 pt-2">
                                <p class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Planos</p>
                            </li>
                            @if (Route::has('jovens.bible.plans.index'))
                                <li>
                                    <a href="{{ route('jovens.bible.plans.index') }}" class="{{ $bSub }} {{ request()->routeIs('jovens.bible.plans.index', 'jovens.bible.plans.show', 'jovens.bible.plans.preview', 'jovens.bible.reader*', 'jovens.bible.plans.recalculate', 'jovens.bible.plans.pdf') ? $bSubOn : $bSubOff }}">
                                        <x-icon name="calendar-days" class="h-3.5 w-3.5 shrink-0 opacity-70" style="duotone" />
                                        <span>Os meus planos</span>
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('jovens.bible.plans.catalog'))
                                <li>
                                    <a href="{{ route('jovens.bible.plans.catalog') }}" class="{{ $bSub }} {{ request()->routeIs('jovens.bible.plans.catalog') ? $bSubOn : $bSubOff }}">
                                        <x-icon name="book" class="h-3.5 w-3.5 shrink-0 opacity-70" style="duotone" />
                                        <span>Catálogo</span>
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('jovens.bible.search'))
                                <li>
                                    <a href="{{ route('jovens.bible.search') }}" class="{{ $bSub }} {{ request()->routeIs('jovens.bible.search', 'jovens.bible.api.search') ? $bSubOn : $bSubOff }}">
                                        <x-icon name="magnifying-glass" class="h-3.5 w-3.5 shrink-0 opacity-70" style="duotone" />
                                        <span>Busca</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (module_enabled('Notificacoes') && Route::has('jovens.notificacoes.index'))
                    <li>
                        <a href="{{ route('jovens.notificacoes.index') }}" @class([$link, request()->routeIs('jovens.notificacoes.*') ? $active : $idle])>
                            <span @class([$iconBox, request()->routeIs('jovens.notificacoes.*') ? $iconActive : $iconIdle])>
                                <x-icon name="bell" style="{{ request()->routeIs('jovens.notificacoes.*') ? 'solid' : 'duotone' }}" class="h-[1.05rem] w-[1.05rem]" />
                            </span>
                            <span>Notificações</span>
                        </a>
                    </li>
                @endif
                @if (module_enabled('Avisos') && Route::has('jovens.avisos.index'))
                    <li>
                        <a href="{{ route('jovens.avisos.index') }}" @class([$link, request()->routeIs('jovens.avisos.*') ? $active : $idle])>
                            <span @class([$iconBox, request()->routeIs('jovens.avisos.*') ? $iconActive : $iconIdle])>
                                <x-module-icon module="Avisos" class="h-5 w-5 {{ request()->routeIs('jovens.avisos.*') ? 'text-blue-600 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400' }}" style="duotone" />
                            </span>
                            <span>Avisos JUBAF</span>
                        </a>
                    </li>
                @endif
                @if (module_enabled('Blog') && Route::has('jovens.blog.index'))
                    <li>
                        <a href="{{ route('jovens.blog.index') }}" @class([$link, request()->routeIs('jovens.blog.*') ? $active : $idle])>
                            <span @class([$iconBox, request()->routeIs('jovens.blog.*') ? $iconActive : $iconIdle])>
                                <x-module-icon module="Blog" class="h-5 w-5 {{ request()->routeIs('jovens.blog.*') ? 'text-blue-600 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400' }}" style="duotone" alt="" />
                            </span>
                            <span>Blog JUBAF</span>
                        </a>
                    </li>
                @endif
                @if (module_enabled('Chat'))
                    <li>
                        <a href="{{ route('jovens.chat.page') }}" @class([$link, request()->routeIs('jovens.chat.*') ? $active : $idle])>
                            <span @class([$iconBox, request()->routeIs('jovens.chat.*') ? $iconActive : $iconIdle])>
                                <x-icon name="messages" style="{{ request()->routeIs('jovens.chat.*') ? 'solid' : 'duotone' }}" class="h-[1.05rem] w-[1.05rem]" />
                            </span>
                            <span>Chat</span>
                        </a>
                    </li>
                @endif
                @if (module_enabled('Igrejas') && Route::has('jovens.igreja.index'))
                    <li>
                        <a href="{{ route('jovens.igreja.index') }}" @class([$link, request()->routeIs('jovens.igreja.*') ? $active : $idle])>
                            <span @class([$iconBox, request()->routeIs('jovens.igreja.*') ? $iconActive : $iconIdle])>
                                <x-module-icon module="Igrejas" class="h-5 w-5 {{ request()->routeIs('jovens.igreja.*') ? 'text-blue-600 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400' }}" style="duotone" />
                            </span>
                            <span>Minha igreja</span>
                        </a>
                    </li>
                @endif
                @if (module_enabled('Secretaria') && Route::has('jovens.secretaria.index'))
                    <li>
                        <a href="{{ route('jovens.secretaria.index') }}" @class([$link, request()->routeIs('jovens.secretaria.*') ? $active : $idle])>
                            <span @class([$iconBox, request()->routeIs('jovens.secretaria.*') ? $iconActive : $iconIdle])>
                                <x-icon name="file-lines" style="{{ request()->routeIs('jovens.secretaria.*') ? 'solid' : 'duotone' }}" class="h-[1.05rem] w-[1.05rem]" />
                            </span>
                            <span>Secretaria</span>
                        </a>
                    </li>
                @endif
                @if (module_enabled('Calendario') && Route::has('jovens.eventos.index') && auth()->user()?->can('calendario.participate'))
                    <li>
                        <a href="{{ route('jovens.eventos.index') }}" @class([$link, request()->routeIs('jovens.eventos.*') ? $active : $idle])>
                            <span @class([$iconBox, request()->routeIs('jovens.eventos.*') ? $iconActive : $iconIdle])>
                                <x-module-icon module="Calendario" class="h-5 w-5 {{ request()->routeIs('jovens.eventos.*') ? 'text-blue-600 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400' }}" style="duotone" />
                            </span>
                            <span>Eventos</span>
                        </a>
                    </li>
                @endif
                @if (Route::has('jovens.wallet.index') && auth()->user()?->can('calendario.participate'))
                    <li>
                        <a href="{{ route('jovens.wallet.index') }}" @class([$link, request()->routeIs('jovens.wallet.*') ? $active : $idle])>
                            <span @class([$iconBox, request()->routeIs('jovens.wallet.*') ? $iconActive : $iconIdle])>
                                <x-icon name="ticket" style="{{ request()->routeIs('jovens.wallet.*') ? 'solid' : 'duotone' }}" class="h-[1.05rem] w-[1.05rem]" />
                            </span>
                            <span>Carteira</span>
                        </a>
                    </li>
                @endif
                @if (module_enabled('Talentos') && Route::has('jovens.talentos.profile.edit') && auth()->user()?->can('talentos.profile.edit'))
                    <li>
                        <a href="{{ route('jovens.talentos.profile.edit') }}" @class([$link, request()->routeIs('jovens.talentos.*') ? $active : $idle])>
                            <span @class([$iconBox, request()->routeIs('jovens.talentos.*') ? $iconActive : $iconIdle])>
                                <x-module-icon module="Talentos" class="h-5 w-5 {{ request()->routeIs('jovens.talentos.*') ? 'text-blue-600 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400' }}" style="duotone" />
                            </span>
                            <span>Banco de talentos</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('jovens.profile.index') }}" @class([$link, request()->routeIs('jovens.profile.*') ? $active : $idle])>
                        <span @class([$iconBox, request()->routeIs('jovens.profile.*') ? $iconActive : $iconIdle])>
                            <x-icon name="user-gear" style="{{ request()->routeIs('jovens.profile.*') ? 'solid' : 'duotone' }}" class="h-[1.05rem] w-[1.05rem]" />
                        </span>
                        <span>Perfil</span>
                    </a>
                </li>
                <li class="pt-2">
                    <a href="{{ route('homepage') }}" @class([$link, $idle])>
                        <span @class([$iconBox, $iconIdle])>
                            <x-icon name="arrow-up-right-from-square" style="duotone" class="h-[1.05rem] w-[1.05rem]" />
                        </span>
                        <span>Site público</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
