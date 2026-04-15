<nav class="sticky top-0 z-50 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border-b border-gray-200 dark:border-slate-700 shadow-sm" id="homepageNavbar">
    <div class="container mx-auto px-4">
        @php
            $currentPath = trim(request()->path(), '/');
            $isHomepage = request()->routeIs('homepage') || $currentPath === '' || $currentPath === 'public';
            $homepageUrl = route('homepage');
            $navbarConfigs = [
                'inicio' => (bool) \App\Models\SystemConfig::get('homepage_navbar_inicio_enabled', true),
                'servicos' => (bool) \App\Models\SystemConfig::get('homepage_navbar_servicos_enabled', true),
                'sobre' => (bool) \App\Models\SystemConfig::get('homepage_navbar_sobre_enabled', true),
                'consulta' => (bool) \App\Models\SystemConfig::get('homepage_navbar_consulta_enabled', true),
                'contato' => (bool) \App\Models\SystemConfig::get('homepage_navbar_contato_enabled', true),
                'diretoria' => (bool) \App\Models\SystemConfig::get('homepage_navbar_diretoria_enabled', true),
                'radio' => (bool) \App\Models\SystemConfig::get('homepage_navbar_radio_enabled', true),
                'devotionals' => (bool) \App\Models\SystemConfig::get('homepage_navbar_devotionals_enabled', true),
            ];
            $demandasConsultaNav = $navbarConfigs['consulta']
                && module_enabled('Demandas')
                && Route::has('demandas.public.consulta');
            $diretoriaNav = ($navbarConfigs['diretoria'] ?? false)
                && (bool) \App\Models\SystemConfig::get('homepage_public_diretoria_enabled', true)
                && Route::has('homepage.diretoria');
            $radioNav = ($navbarConfigs['radio'] ?? false)
                && (bool) \App\Models\SystemConfig::get('homepage_public_radio_enabled', true)
                && Route::has('radio');
            $devotionalsNav = ($navbarConfigs['devotionals'] ?? false)
                && (bool) \App\Models\SystemConfig::get('homepage_public_devotionals_enabled', true)
                && Route::has('devocionais.index');
            $bibleNav = (bool) \App\Models\SystemConfig::get('homepage_bible_navbar_enabled', false)
                && module_enabled('Bible')
                && Route::has('bible.public.index');
            $bibleNavLabel = \App\Models\SystemConfig::get('homepage_bible_navbar_label', 'Bíblia');
            $blogNav = module_enabled('Blog') && Route::has('blog.index');
            $eventosNav = module_enabled('Calendario') && Route::has('eventos.index');
            $igrejasPublicNav = module_enabled('Igrejas') && Route::has('igrejas.public.index');
            $talentosPublicNav = module_enabled('Talentos') && Route::has('talentos.public');
            $navInstCount = (int) $navbarConfigs['sobre'] + (int) $navbarConfigs['contato'];
            $navConteudoCount = (int) $devotionalsNav + (int) $radioNav + (int) $blogNav + (int) $bibleNav;
            $navRedeCount = (int) $diretoriaNav + (int) $eventosNav + (int) $igrejasPublicNav + (int) $talentosPublicNav + (int) $demandasConsultaNav;
        @endphp
        <div class="flex items-center justify-between h-20">
            <!-- Logo (escuro em fundo claro, claro em fundo escuro) -->
            <a href="{{ route('homepage') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <img src="{{ \App\Support\SiteBranding::logoDarkUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-12 w-auto max-w-[200px] object-contain dark:hidden">
                <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-12 w-auto max-w-[200px] object-contain hidden dark:block">
            </a>

            <!-- Desktop Navigation (agrupada: menos itens no topo, submenus ao hover) -->
            <div class="hidden lg:flex flex-1 min-w-0 items-center justify-center gap-1 px-2 xl:gap-2">
                @if($navbarConfigs['inicio'])
                <a href="{{ $isHomepage ? '#inicio' : $homepageUrl . '#inicio' }}" class="nav-link whitespace-nowrap rounded-lg px-2.5 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 hover:text-blue-700 dark:hover:bg-slate-700 dark:hover:text-blue-400 font-medium transition-colors relative group" data-anchor="inicio">
                    <span class="flex items-center gap-1.5">
                        <x-icon name="house" style="duotone" class="w-4 h-4 shrink-0 opacity-80" />
                        Início
                    </span>
                </a>
                @endif
                @if($navbarConfigs['servicos'])
                <a href="{{ $isHomepage ? '#servicos' : $homepageUrl . '#servicos' }}" class="nav-link whitespace-nowrap rounded-lg px-2.5 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 hover:text-blue-700 dark:hover:bg-slate-700 dark:hover:text-blue-400 font-medium transition-colors relative group" data-anchor="servicos">
                    <span class="flex items-center gap-1.5">
                        <x-icon name="grid-2" style="duotone" class="w-4 h-4 shrink-0 opacity-80" />
                        Serviços
                    </span>
                </a>
                @endif

                {{-- Institucional: submenu se 2+ itens; senão link único --}}
                @if($navInstCount >= 2)
                <div class="relative group/drop" data-homepage-nav-dropdown>
                    <button type="button" data-homepage-nav-dropdown-trigger class="inline-flex items-center gap-1 rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700" aria-haspopup="true" aria-expanded="false" aria-controls="homepage-nav-dd-institucional">
                        <x-icon name="building-columns" style="duotone" class="w-4 h-4 shrink-0 opacity-80" />
                        Institucional
                        <x-icon name="chevron-down" class="homepage-nav-dd-chevron w-3 h-3 opacity-60 transition-transform group-hover/drop:rotate-180" style="duotone" />
                    </button>
                    <div id="homepage-nav-dd-institucional" class="homepage-nav-dropdown invisible absolute left-0 top-full z-50 pt-1 opacity-0 transition-all duration-150 group-hover/drop:visible group-hover/drop:opacity-100 group-focus-within/drop:visible group-focus-within/drop:opacity-100">
                        <div class="min-w-[13rem] rounded-xl border border-gray-200 bg-white py-1.5 shadow-xl dark:border-slate-600 dark:bg-slate-800">
                            @if($navbarConfigs['sobre'])
                            <a href="{{ route('sobre') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-slate-700/80">
                                <x-icon name="circle-info" style="duotone" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                Sobre
                            </a>
                            @endif
                            @if($navbarConfigs['contato'])
                            <a href="{{ route('contato') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-slate-700/80">
                                <x-icon name="envelope" style="duotone" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                Contato
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @elseif($navbarConfigs['sobre'])
                <a href="{{ route('sobre') }}" class="whitespace-nowrap rounded-lg px-2.5 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 hover:text-blue-700 dark:hover:bg-slate-700 dark:hover:text-blue-400 font-medium transition-colors">
                    <span class="flex items-center gap-1.5"><x-icon name="circle-info" style="duotone" class="w-4 h-4 shrink-0 opacity-80" /> Sobre</span>
                </a>
                @elseif($navbarConfigs['contato'])
                <a href="{{ route('contato') }}" class="whitespace-nowrap rounded-lg px-2.5 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 hover:text-blue-700 dark:hover:bg-slate-700 dark:hover:text-blue-400 font-medium transition-colors">
                    <span class="flex items-center gap-1.5"><x-icon name="envelope" style="duotone" class="w-4 h-4 shrink-0 opacity-80" /> Contato</span>
                </a>
                @endif

                {{-- Conteúdo: multimédia e leitura --}}
                @if($navConteudoCount >= 2)
                <div class="relative group/drop" data-homepage-nav-dropdown>
                    <button type="button" data-homepage-nav-dropdown-trigger class="inline-flex items-center gap-1 rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700" aria-haspopup="true" aria-expanded="false" aria-controls="homepage-nav-dd-conteudo">
                        <x-icon name="layer-group" style="duotone" class="w-4 h-4 shrink-0 opacity-80" />
                        Conteúdo
                        <x-icon name="chevron-down" class="homepage-nav-dd-chevron w-3 h-3 opacity-60 transition-transform group-hover/drop:rotate-180" style="duotone" />
                    </button>
                    <div id="homepage-nav-dd-conteudo" class="homepage-nav-dropdown invisible absolute left-0 top-full z-50 pt-1 opacity-0 transition-all duration-150 group-hover/drop:visible group-hover/drop:opacity-100 group-focus-within/drop:visible group-focus-within/drop:opacity-100">
                        <div class="min-w-[14rem] rounded-xl border border-gray-200 bg-white py-1.5 shadow-xl dark:border-slate-600 dark:bg-slate-800">
                            @if($devotionalsNav)
                            <a href="{{ route('devocionais.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-slate-700/80">
                                <x-icon name="book-open" style="duotone" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                Devocionais
                            </a>
                            @endif
                            @if($radioNav)
                            <a href="{{ route('radio') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-slate-700/80">
                                <x-icon name="tower-broadcast" style="duotone" class="w-4 h-4 text-purple-600 dark:text-purple-400" />
                                Rádio
                            </a>
                            @endif
                            @if($blogNav)
                            <a href="{{ route('blog.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-slate-700/80">
                                <x-icon name="newspaper" style="duotone" class="w-4 h-4 text-indigo-600 dark:text-indigo-400" />
                                Blog
                            </a>
                            @endif
                            @if($bibleNav)
                            <a href="{{ route('bible.public.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-slate-700/80">
                                <x-icon name="book-bible" style="duotone" class="w-4 h-4 text-amber-700 dark:text-amber-400" />
                                {{ $bibleNavLabel }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @elseif($navConteudoCount === 1)
                    @if($devotionalsNav)
                    <a href="{{ route('devocionais.index') }}" class="whitespace-nowrap rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700"><span class="flex items-center gap-1.5"><x-icon name="book-open" class="w-4 h-4" style="duotone" /> Devocionais</span></a>
                    @elseif($radioNav)
                    <a href="{{ route('radio') }}" class="whitespace-nowrap rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700"><span class="flex items-center gap-1.5"><x-icon name="tower-broadcast" class="w-4 h-4" style="duotone" /> Rádio</span></a>
                    @elseif($blogNav)
                    <a href="{{ route('blog.index') }}" class="whitespace-nowrap rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700"><span class="flex items-center gap-1.5"><x-icon name="newspaper" class="w-4 h-4" style="duotone" /> Blog</span></a>
                    @elseif($bibleNav)
                    <a href="{{ route('bible.public.index') }}" class="whitespace-nowrap rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700"><span class="flex items-center gap-1.5"><x-icon name="book-bible" class="w-4 h-4" style="duotone" /> {{ $bibleNavLabel }}</span></a>
                    @endif
                @endif

                {{-- Na JUBAF: diretoria, eventos, congregações, talentos, demandas --}}
                @if($navRedeCount >= 2)
                <div class="relative group/drop" data-homepage-nav-dropdown>
                    <button type="button" data-homepage-nav-dropdown-trigger class="inline-flex items-center gap-1 rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700" aria-haspopup="true" aria-expanded="false" aria-controls="homepage-nav-dd-jubaf">
                        <x-icon name="users" class="w-4 h-4 shrink-0 opacity-80" style="duotone" />
                        Na JUBAF
                        <x-icon name="chevron-down" class="homepage-nav-dd-chevron w-3 h-3 opacity-60 transition-transform group-hover/drop:rotate-180" style="duotone" />
                    </button>
                    <div id="homepage-nav-dd-jubaf" class="homepage-nav-dropdown invisible absolute right-0 top-full z-50 pt-1 opacity-0 transition-all duration-150 group-hover/drop:visible group-hover/drop:opacity-100 group-focus-within/drop:visible group-focus-within/drop:opacity-100 lg:left-0 lg:right-auto">
                        <div class="min-w-[15rem] rounded-xl border border-gray-200 bg-white py-1.5 shadow-xl dark:border-slate-600 dark:bg-slate-800">
                            @if($diretoriaNav)
                            <a href="{{ route('homepage.diretoria') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-slate-700/80">
                                <x-icon name="users" style="duotone" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                Diretoria
                            </a>
                            @endif
                            @if($eventosNav)
                            <a href="{{ route('eventos.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-slate-700/80">
                                <x-icon name="calendar-days" style="duotone" class="w-4 h-4 text-teal-600 dark:text-teal-400" />
                                Eventos
                            </a>
                            @endif
                            @if($igrejasPublicNav)
                            <a href="{{ route('igrejas.public.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-slate-700/80">
                                <x-module-icon module="Igrejas" class="h-4 w-4 shrink-0" />
                                Congregações
                            </a>
                            @endif
                            @if($talentosPublicNav)
                            <a href="{{ route('talentos.public') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-slate-700/80">
                                <x-module-icon module="Talentos" class="h-4 w-4 shrink-0" />
                                Talentos
                            </a>
                            @endif
                            @if($demandasConsultaNav)
                            <a href="{{ route('demandas.public.consulta') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-slate-700/80">
                                <x-icon name="magnifying-glass" style="duotone" class="w-4 h-4 text-sky-600 dark:text-sky-400" />
                                Consultar demanda
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @elseif($navRedeCount === 1)
                    @if($diretoriaNav)
                    <a href="{{ route('homepage.diretoria') }}" class="whitespace-nowrap rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700"><span class="flex items-center gap-1.5"><x-icon name="users" class="w-4 h-4" style="duotone" /> Diretoria</span></a>
                    @elseif($eventosNav)
                    <a href="{{ route('eventos.index') }}" class="whitespace-nowrap rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700"><span class="flex items-center gap-1.5"><x-icon name="calendar-days" class="w-4 h-4 text-teal-600 dark:text-teal-400" style="duotone" /> Eventos</span></a>
                    @elseif($igrejasPublicNav)
                    <a href="{{ route('igrejas.public.index') }}" class="whitespace-nowrap rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700"><span class="flex items-center gap-1.5"><x-module-icon module="Igrejas" class="h-4 w-4" /> Congregações</span></a>
                    @elseif($talentosPublicNav)
                    <a href="{{ route('talentos.public') }}" class="whitespace-nowrap rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700"><span class="flex items-center gap-1.5"><x-module-icon module="Talentos" class="h-4 w-4" /> Talentos</span></a>
                    @elseif($demandasConsultaNav)
                    <a href="{{ route('demandas.public.consulta') }}" class="whitespace-nowrap rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700"><span class="flex items-center gap-1.5"><x-icon name="magnifying-glass" class="w-4 h-4" style="duotone" /> Consultar demanda</span></a>
                    @endif
                @endif
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center gap-4">
                <!-- Theme Toggle -->
                <button type="button" id="darkModeToggle" onclick="toggleTheme()" class="relative w-12 h-12 rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 flex items-center justify-center transition-all duration-300 hover:scale-110 group" aria-label="Alternar tema">
                    <span id="theme-icon-sun" class="absolute transition-all duration-300">
                        <x-icon name="sun" style="duotone" class="w-5 h-5 text-yellow-500" />
                    </span>
                    <span id="theme-icon-moon" class="absolute transition-all duration-300 hidden">
                        <x-icon name="moon" style="duotone" class="w-5 h-5 text-blue-400" />
                    </span>
                </button>

                @auth
                    <a href="{{ route(get_dashboard_route()) }}" class="hidden md:flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 font-medium transition-colors">
                        <x-icon name="bolt" style="duotone" class="w-5 h-5" />
                        <span>Painel</span>
                    </a>

                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                            <x-icon name="user" style="duotone" class="w-5 h-5" />
                            <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                            <x-icon name="chevron-down" class="w-4 h-4 transition-transform group-hover:rotate-180" />
                        </button>

                        <div class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-200 dark:border-slate-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="p-4 border-b border-gray-200 dark:border-slate-700">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="p-2">
                                @php
                                    $profileName = get_profile_route();
                                    $profileRoute = \Illuminate\Support\Facades\Route::has($profileName) ? route($profileName) : null;
                                @endphp

                                @if($profileRoute)
                                <a href="{{ $profileRoute }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 transition-colors">
                                    <x-icon name="user-gear" style="duotone" class="w-5 h-5" />
                                    <span>Perfil</span>
                                </a>
                                @endif

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 transition-colors">
                                        <x-icon name="right-from-bracket" style="duotone" class="w-5 h-5" />
                                        <span>Sair</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden md:inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                        <x-icon name="right-to-bracket" style="duotone" class="w-4 h-4" />
                        <span>Entrar</span>
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button id="mobileMenuToggle" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                    <x-icon name="bars" class="w-6 h-6 text-gray-700 dark:text-gray-300" />
                </button>
            </div>
        </div>

        <!-- Mobile Menu (mesmos grupos que no desktop) -->
        <div id="mobileMenu" class="hidden lg:hidden pb-4 border-t border-gray-200 dark:border-slate-700 mt-4">
            <div class="flex flex-col gap-2 pt-4">
                @if($navbarConfigs['inicio'] || $navbarConfigs['servicos'])
                <details class="rounded-xl border border-gray-200 bg-gray-50/80 dark:border-slate-600 dark:bg-slate-800/50 group" @if($navbarConfigs['inicio']) open @endif>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-2 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-2">
                            <x-icon name="house" style="duotone" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                            Página inicial
                        </span>
                        <x-icon name="chevron-down" class="w-4 h-4 shrink-0 text-gray-500 transition-transform group-open:rotate-180" style="duotone" />
                    </summary>
                    <div class="space-y-1 border-t border-gray-200 px-2 py-2 dark:border-slate-600">
                        @if($navbarConfigs['inicio'])
                        <a href="{{ $isHomepage ? '#inicio' : $homepageUrl . '#inicio' }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700" data-anchor="inicio">
                            <x-icon name="house" style="duotone" class="w-4 h-4 opacity-80" />
                            Início
                        </a>
                        @endif
                        @if($navbarConfigs['servicos'])
                        <a href="{{ $isHomepage ? '#servicos' : $homepageUrl . '#servicos' }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700" data-anchor="servicos">
                            <x-icon name="grid-2" style="duotone" class="w-4 h-4 opacity-80" />
                            Serviços
                        </a>
                        @endif
                    </div>
                </details>
                @endif

                @if($navInstCount > 0)
                <details class="rounded-xl border border-gray-200 bg-gray-50/80 dark:border-slate-600 dark:bg-slate-800/50 group">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-2 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-2">
                            <x-icon name="building-columns" style="duotone" class="w-5 h-5 text-slate-600 dark:text-slate-300" />
                            Institucional
                        </span>
                        <x-icon name="chevron-down" class="w-4 h-4 shrink-0 text-gray-500 transition-transform group-open:rotate-180" style="duotone" />
                    </summary>
                    <div class="space-y-1 border-t border-gray-200 px-2 py-2 dark:border-slate-600">
                        @if($navbarConfigs['sobre'])
                        <a href="{{ route('sobre') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700">
                            <x-icon name="circle-info" style="duotone" class="w-4 h-4" />
                            Sobre
                        </a>
                        @endif
                        @if($navbarConfigs['contato'])
                        <a href="{{ route('contato') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700">
                            <x-icon name="envelope" style="duotone" class="w-4 h-4" />
                            Contato
                        </a>
                        @endif
                    </div>
                </details>
                @endif

                @if($navConteudoCount > 0)
                <details class="rounded-xl border border-gray-200 bg-gray-50/80 dark:border-slate-600 dark:bg-slate-800/50 group">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-2 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-2">
                            <x-icon name="layer-group" style="duotone" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                            Conteúdo
                        </span>
                        <x-icon name="chevron-down" class="w-4 h-4 shrink-0 text-gray-500 transition-transform group-open:rotate-180" style="duotone" />
                    </summary>
                    <div class="space-y-1 border-t border-gray-200 px-2 py-2 dark:border-slate-600">
                        @if($devotionalsNav)
                        <a href="{{ route('devocionais.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700">
                            <x-icon name="book-open" style="duotone" class="w-4 h-4" />
                            Devocionais
                        </a>
                        @endif
                        @if($radioNav)
                        <a href="{{ route('radio') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700">
                            <x-icon name="tower-broadcast" style="duotone" class="w-4 h-4" />
                            Rádio
                        </a>
                        @endif
                        @if($blogNav)
                        <a href="{{ route('blog.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700">
                            <x-icon name="newspaper" style="duotone" class="w-4 h-4" />
                            Blog
                        </a>
                        @endif
                        @if($bibleNav)
                        <a href="{{ route('bible.public.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700">
                            <x-icon name="book-bible" style="duotone" class="w-4 h-4" />
                            {{ $bibleNavLabel }}
                        </a>
                        @endif
                    </div>
                </details>
                @endif

                @if($navRedeCount > 0)
                <details class="rounded-xl border border-gray-200 bg-gray-50/80 dark:border-slate-600 dark:bg-slate-800/50 group">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-2 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-2">
                            <x-icon name="users" style="duotone" class="w-5 h-5 text-teal-600 dark:text-teal-400" />
                            Na JUBAF
                        </span>
                        <x-icon name="chevron-down" class="w-4 h-4 shrink-0 text-gray-500 transition-transform group-open:rotate-180" style="duotone" />
                    </summary>
                    <div class="space-y-1 border-t border-gray-200 px-2 py-2 dark:border-slate-600">
                        @if($diretoriaNav)
                        <a href="{{ route('homepage.diretoria') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700">
                            <x-icon name="users" style="duotone" class="w-4 h-4" />
                            Diretoria
                        </a>
                        @endif
                        @if($eventosNav)
                        <a href="{{ route('eventos.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700">
                            <x-icon name="calendar-days" style="duotone" class="w-4 h-4 text-teal-600 dark:text-teal-400" />
                            Eventos
                        </a>
                        @endif
                        @if($igrejasPublicNav)
                        <a href="{{ route('igrejas.public.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700">
                            <x-module-icon module="Igrejas" class="h-4 w-4 shrink-0" />
                            Congregações
                        </a>
                        @endif
                        @if($talentosPublicNav)
                        <a href="{{ route('talentos.public') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700">
                            <x-module-icon module="Talentos" class="h-4 w-4 shrink-0" />
                            Talentos
                        </a>
                        @endif
                        @if($demandasConsultaNav)
                        <a href="{{ route('demandas.public.consulta') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-white dark:text-gray-300 dark:hover:bg-slate-700">
                            <x-icon name="magnifying-glass" style="duotone" class="w-4 h-4" />
                            Consultar demanda
                        </a>
                        @endif
                    </div>
                </details>
                @endif

                @guest
                    <div class="pt-2 border-t border-gray-200 dark:border-slate-700 mt-2 space-y-2">
                        <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                            <x-icon name="right-to-bracket" style="duotone" class="w-4 h-4" />
                            <span>Entrar</span>
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<style>
    /* Clique/toque: submenu abre com .is-open (hover sozinho não basta em muitos dispositivos) */
    #homepageNavbar [data-homepage-nav-dropdown].is-open .homepage-nav-dropdown {
        visibility: visible;
        opacity: 1;
    }
    #homepageNavbar [data-homepage-nav-dropdown].is-open .homepage-nav-dd-chevron {
        transform: rotate(180deg);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Desktop: submenus por clique + fechar fora / Escape
    const navDropdownRoots = document.querySelectorAll('#homepageNavbar [data-homepage-nav-dropdown]');
    function closeAllHomepageNavDropdowns() {
        navDropdownRoots.forEach(function (root) {
            root.classList.remove('is-open');
            var t = root.querySelector('[data-homepage-nav-dropdown-trigger]');
            if (t) {
                t.setAttribute('aria-expanded', 'false');
            }
        });
    }
    navDropdownRoots.forEach(function (root) {
        var trigger = root.querySelector('[data-homepage-nav-dropdown-trigger]');
        if (!trigger) {
            return;
        }
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var opening = !root.classList.contains('is-open');
            closeAllHomepageNavDropdowns();
            if (opening) {
                root.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });
    });
    document.addEventListener('click', function () {
        closeAllHomepageNavDropdowns();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllHomepageNavDropdowns();
        }
    });
    navDropdownRoots.forEach(function (root) {
        root.addEventListener('click', function (e) {
            e.stopPropagation();
        });
        root.querySelectorAll('.homepage-nav-dropdown a').forEach(function (a) {
            a.addEventListener('click', function () {
                closeAllHomepageNavDropdowns();
            });
        });
    });

    // Detectar se estamos na homepage
    const currentPath = window.location.pathname.replace(/\/$/, ''); // Remove trailing slash
    const isHomepage = currentPath === '' || currentPath === '/public' || currentPath === '/' || currentPath.endsWith('/public');
    const homepageUrl = '{{ route("homepage") }}';

    // Smooth Scroll - Funciona tanto na homepage quanto em outras páginas
    document.querySelectorAll('a[data-anchor], a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            const anchorName = this.getAttribute('data-anchor');

            // Se não estiver na homepage e o link tiver âncora, redirecionar para homepage
            if (!isHomepage && (href.startsWith('#') || anchorName)) {
                e.preventDefault();
                const anchor = anchorName || href.substring(1);
                window.location.href = homepageUrl + '#' + anchor;
                return;
            }

            // Se estiver na homepage, fazer scroll suave
            if (isHomepage && href.startsWith('#') && href.length > 1) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const offsetTop = target.offsetTop - 80;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                    // Fechar menu mobile
                    if (mobileMenu) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            }
        });
    });

    // Active Nav Link on Scroll (apenas na homepage)
    if (isHomepage) {
        const navLinks = document.querySelectorAll('.nav-link');
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const scrollPos = window.pageYOffset + 100;

            sections.forEach(section => {
                const top = section.offsetTop;
                const bottom = top + section.offsetHeight;
                const id = section.getAttribute('id');

                if (scrollPos >= top && scrollPos < bottom) {
                    navLinks.forEach(link => {
                        link.classList.remove('text-blue-700', 'dark:text-blue-400');
                        const linkHref = link.getAttribute('href');
                        const linkAnchor = link.getAttribute('data-anchor');
                        if (linkHref === `#${id}` || linkAnchor === id) {
                            link.classList.add('text-blue-700', 'dark:text-blue-400');
                        }
                    });
                }
            });
        });
    } else {
        // Se não estiver na homepage, destacar o link baseado na URL atual
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');

        // Remover destaque de todos os links quando não estiver na homepage
        navLinks.forEach(link => {
            link.classList.remove('text-blue-700', 'dark:text-blue-400');
        });
    }
});
</script>
