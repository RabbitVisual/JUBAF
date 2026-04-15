@php
    use Modules\Calendario\App\Models\CalendarEvent;
    use Modules\Igrejas\App\Models\Church;
    use Modules\Secretaria\App\Models\Meeting;

    $hasHomepageMenu = module_enabled('Homepage')
        && Route::has('diretoria.homepage.index')
        && auth()->user()?->can('homepage.edit');
    $hasCarouselMenu = user_is_diretoria_executive() && Route::has('diretoria.carousel.index');
    $hasBoardMenu = module_enabled('Homepage')
        && Route::has('diretoria.board-members.index')
        && auth()->user()?->can('board_members.view');
    $hasDevotionalsMenu = module_enabled('Homepage')
        && Route::has('diretoria.devotionals.index')
        && auth()->user()?->can('devotionals.view');
    $hasHomepageContactsMenu = $hasHomepageMenu && Route::has('diretoria.homepage.contacts.index');
    $hasHomepageNewsletterMenu = $hasHomepageMenu && Route::has('diretoria.homepage.newsletter.index');
    $siteGroupVisible = $hasHomepageMenu || $hasCarouselMenu || $hasBoardMenu || $hasDevotionalsMenu;
    $siteGroupOpen = request()->routeIs(
        'diretoria.homepage.*',
        'diretoria.carousel.*',
        'diretoria.board-members.*',
        'diretoria.devotionals.*',
    );

    $hasIgrejasMenu = module_enabled('Igrejas')
        && Route::has('diretoria.igrejas.dashboard')
        && auth()->user()?->can('viewAny', Church::class);
    $hasSecretariaMenu = module_enabled('Secretaria')
        && Route::has('diretoria.secretaria.dashboard')
        && auth()->user()?->can('viewAny', Meeting::class);
    $orgGroupVisible = $hasIgrejasMenu || $hasSecretariaMenu;
    $orgGroupOpen = request()->routeIs('diretoria.igrejas.*', 'diretoria.secretaria.*');

    $hasFinanceiroMenu = module_enabled('Financeiro')
        && Route::has('diretoria.financeiro.dashboard')
        && auth()->user()?->can('financeiro.dashboard.view');
    $hasGatewayMenu = module_enabled('Gateway')
        && Route::has('diretoria.gateway.dashboard')
        && auth()->user()?->can('gateway.dashboard.view');
    $hasCalendarioDiretoriaMenu = module_enabled('Calendario')
        && Route::has('diretoria.calendario.dashboard')
        && auth()->user()?->can('viewAny', CalendarEvent::class);
    $hasTalentosMenu = module_enabled('Talentos')
        && Route::has('diretoria.talentos.dashboard')
        && auth()->user()
        && (
            auth()->user()->can('talentos.directory.view')
            || auth()->user()->can('talentos.assignments.view')
        );
    $planGroupVisible = $hasFinanceiroMenu || $hasGatewayMenu || $hasCalendarioDiretoriaMenu || $hasTalentosMenu;
    $planGroupOpen = request()->routeIs('diretoria.financeiro.*', 'diretoria.gateway.*', 'diretoria.calendario.*', 'diretoria.talentos.*');

    $hasProfileDataRequestsMenu = (user_is_diretoria_executive() || user_can_access_admin_panel()) && Route::has('diretoria.profile-data-requests.index');
    $pendingProfileDataRequestsCount = 0;
    if ($hasProfileDataRequestsMenu && auth()->check()) {
        $pendingProfileDataRequestsCount = \App\Models\ProfileSensitiveDataRequest::query()
            ->where('status', \App\Models\ProfileSensitiveDataRequest::STATUS_PENDING)
            ->count();
    }

    $hasAdminUsers = user_is_diretoria_executive() && Route::has('diretoria.users.index');
    $hasAdminRoles = user_is_diretoria_executive() && Route::has('diretoria.roles.index');
    $hasAdminPerms = user_is_diretoria_executive() && Route::has('diretoria.permissions.index');
    $hasAdminModules = user_is_diretoria_executive() && Route::has('diretoria.modules.index');
    $hasAdminHub = user_is_diretoria_executive() && Route::has('diretoria.seguranca.hub');
    $adminGroupVisible = $hasAdminUsers || $hasAdminRoles || $hasAdminPerms || $hasAdminModules || $hasAdminHub || $hasProfileDataRequestsMenu;
    $adminGroupOpen = request()->routeIs(
        'diretoria.users.*',
        'diretoria.roles.*',
        'diretoria.permissions.*',
        'diretoria.modules.*',
        'diretoria.seguranca.*',
        'diretoria.profile-data-requests.*',
    );

    $hasAvisosMenu = module_enabled('Avisos')
        && Route::has('diretoria.avisos.index')
        && auth()->user()?->can('avisos.view');
    $hasNotificacoesMenu = module_enabled('Notificacoes') && Route::has('diretoria.notificacoes.index');
    $hasChatMenu = module_enabled('Chat') && Route::has('diretoria.chat.index');
    $hasBlogMenu = module_enabled('Blog')
        && Route::has('diretoria.blog.index')
        && auth()->check()
        && auth()->user()->can('viewAny', \Modules\Blog\App\Models\BlogPost::class);
    $commsGroupVisible = $hasAvisosMenu || $hasNotificacoesMenu || $hasChatMenu || $hasBlogMenu;
    $commsGroupOpen = request()->routeIs('diretoria.avisos.*', 'diretoria.notificacoes.*', 'diretoria.chat.*', 'diretoria.blog.*');

    $bibleOpen = bible_route_is('diretoria.bible.*');
    $bibleVersionsActive = bible_route_is(
        'diretoria.bible.index',
        'diretoria.bible.create',
        'diretoria.bible.store',
        'diretoria.bible.show',
        'diretoria.bible.edit',
        'diretoria.bible.update',
        'diretoria.bible.book',
        'diretoria.bible.chapter',
        'diretoria.bible.chapter-audio.index',
        'diretoria.bible.chapter-audio.template',
    );
    $bibleImportActive = bible_route_is('diretoria.bible.import', 'diretoria.bible.import.store');
    $biblePlansActive = bible_route_is('diretoria.bible.plans.*');
    $bibleReportsActive = bible_route_is('diretoria.bible.reports.church-plan');
@endphp

<aside class="flex flex-col h-full bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700">
    <style>
        aside::-webkit-scrollbar { width: 6px; }
        aside::-webkit-scrollbar-track { background: rgb(241 245 249); border-radius: 3px; }
        aside::-webkit-scrollbar-thumb { background: rgb(203 213 225); border-radius: 3px; }
        aside::-webkit-scrollbar-thumb:hover { background: rgb(148 163 184); }
        .dark aside::-webkit-scrollbar-track { background: rgb(30 41 59); }
        .dark aside::-webkit-scrollbar-thumb { background: rgb(71 85 105); }
        .dark aside::-webkit-scrollbar-thumb:hover { background: rgb(100 116 139); }
        details.diretoria-nav-details > summary { list-style: none; }
        details.diretoria-nav-details > summary::-webkit-details-marker { display: none; }
        details.diretoria-nav-details[open] .diretoria-nav-chevron { transform: rotate(180deg); }
    </style>

    <div class="flex items-center justify-between p-4 lg:p-6 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                <x-icon name="grid-2" class="w-6 h-6 text-white" style="duotone" />
            </div>
            <div>
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">JUBAF</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Painel da Diretoria</p>
            </div>
        </div>
        <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-slate-700 dark:hover:text-gray-300">
            <x-icon name="xmark" class="w-5 h-5" style="duotone" />
            <span class="sr-only">Fechar menu</span>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        {{-- Início --}}
        <a href="{{ route('diretoria.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('diretoria.dashboard*') ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700' }}">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ request()->routeIs('diretoria.dashboard*') ? 'bg-indigo-500 dark:bg-indigo-600' : 'bg-gray-100 dark:bg-slate-700' }}">
                <x-icon name="chart-line" class="w-5 h-5 {{ request()->routeIs('diretoria.dashboard*') ? 'text-white' : 'text-gray-600 dark:text-gray-400' }}" style="duotone" />
            </div>
            <span class="flex-1">Dashboard</span>
        </a>

        {{-- Site público: homepage, carrossel, diretoria no site, devocionais --}}
        @if($siteGroupVisible)
        <details class="diretoria-nav-details rounded-lg group" @if($siteGroupOpen) open @endif>
            <summary class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer select-none {{ $siteGroupOpen ? 'bg-blue-100 text-blue-900 dark:bg-blue-900/30 dark:text-blue-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700' }}">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ $siteGroupOpen ? 'bg-blue-600 dark:bg-blue-700' : 'bg-gray-100 dark:bg-slate-700' }}">
                    <x-icon name="globe" class="w-5 h-5 {{ $siteGroupOpen ? 'text-white' : 'text-gray-600 dark:text-gray-400' }}" style="duotone" />
                </div>
                <span class="flex-1 text-left">Site público</span>
                <x-icon name="chevron-down" class="diretoria-nav-chevron w-4 h-4 shrink-0 text-blue-800/70 dark:text-blue-200/80 transition-transform duration-200" style="duotone" />
            </summary>
            <div class="mt-1 mb-1 ml-3 pl-3 space-y-3 border-l-2 border-blue-200/90 dark:border-blue-800/60">
                @if($hasHomepageMenu || $hasCarouselMenu)
                <div class="space-y-0.5">
                    <p class="px-2 pt-1 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Página inicial</p>
                    @if($hasHomepageMenu)
                    <a href="{{ route('diretoria.homepage.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.homepage.index') ? 'bg-blue-50 text-blue-900 dark:bg-blue-950/50 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-module-icon module="Homepage" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                        Homepage (secções)
                    </a>
                    @endif
                    @if($hasHomepageContactsMenu)
                    <a href="{{ route('diretoria.homepage.contacts.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.homepage.contacts.*') ? 'bg-blue-50 text-blue-900 dark:bg-blue-950/50 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-icon name="inbox" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                        Mensagens (contato)
                    </a>
                    @endif
                    @if($hasHomepageNewsletterMenu)
                    <a href="{{ route('diretoria.homepage.newsletter.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.homepage.newsletter.*') ? 'bg-blue-50 text-blue-900 dark:bg-blue-950/50 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-icon name="envelope" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                        Newsletter
                    </a>
                    @endif
                    @if($hasCarouselMenu)
                    <a href="{{ route('diretoria.carousel.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.carousel.*') ? 'bg-blue-50 text-blue-900 dark:bg-blue-950/50 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-icon name="images" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                        Carrossel
                    </a>
                    @endif
                </div>
                @endif
                @if($hasBoardMenu || $hasDevotionalsMenu)
                <div class="space-y-0.5 pb-1">
                    <p class="px-2 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Conteúdo editorial</p>
                    @if($hasBoardMenu)
                    <a href="{{ route('diretoria.board-members.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.board-members.*') ? 'bg-blue-50 text-blue-900 dark:bg-blue-950/50 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-icon name="users" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                        Membros (página pública)
                    </a>
                    @endif
                    @if($hasDevotionalsMenu)
                    <a href="{{ route('diretoria.devotionals.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.devotionals.*') ? 'bg-blue-50 text-blue-900 dark:bg-blue-950/50 dark:text-blue-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-icon name="book-open" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                        Devocionais
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </details>
        @endif

        {{-- Organização: igrejas e secretaria --}}
        @if($orgGroupVisible)
        <details class="diretoria-nav-details rounded-lg group" @if($orgGroupOpen) open @endif>
            <summary class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer select-none {{ $orgGroupOpen ? 'bg-cyan-100 text-cyan-900 dark:bg-cyan-900/30 dark:text-cyan-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700' }}">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ $orgGroupOpen ? 'bg-cyan-600 dark:bg-cyan-700' : 'bg-gray-100 dark:bg-slate-700' }}">
                    <x-icon name="sitemap" class="w-5 h-5 {{ $orgGroupOpen ? 'text-white' : 'text-gray-600 dark:text-gray-400' }}" style="duotone" />
                </div>
                <span class="flex-1 text-left">Organização</span>
                <x-icon name="chevron-down" class="diretoria-nav-chevron w-4 h-4 shrink-0 text-cyan-800/70 dark:text-cyan-200/80 transition-transform duration-200" style="duotone" />
            </summary>
            <div class="mt-1 mb-1 ml-3 pl-3 space-y-0.5 border-l-2 border-cyan-200/90 dark:border-cyan-800/60 pb-1">
                @if($hasIgrejasMenu)
                <a href="{{ route('diretoria.igrejas.dashboard') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.igrejas.*') ? 'bg-cyan-50 text-cyan-900 dark:bg-cyan-950/50 dark:text-cyan-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                    <x-module-icon module="Igrejas" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                    Igrejas
                </a>
                @endif
                @if($hasSecretariaMenu)
                <a href="{{ route('diretoria.secretaria.dashboard') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.secretaria.*') ? 'bg-cyan-50 text-cyan-900 dark:bg-cyan-950/50 dark:text-cyan-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                    <x-module-icon module="Secretaria" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                    Secretaria
                </a>
                @endif
            </div>
        </details>
        @endif

        @if($planGroupVisible)
        <details class="diretoria-nav-details rounded-lg group" @if($planGroupOpen) open @endif>
            <summary class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer select-none {{ $planGroupOpen ? 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/30 dark:text-emerald-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700' }}">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ $planGroupOpen ? 'bg-emerald-600 dark:bg-emerald-700' : 'bg-gray-100 dark:bg-slate-700' }}">
                    <x-icon name="chart-line" class="w-5 h-5 {{ $planGroupOpen ? 'text-white' : 'text-gray-600 dark:text-gray-400' }}" style="duotone" />
                </div>
                <span class="flex-1 text-left">Finanças e eventos</span>
                <x-icon name="chevron-down" class="diretoria-nav-chevron w-4 h-4 shrink-0 text-emerald-800/70 dark:text-emerald-200/80 transition-transform duration-200" style="duotone" />
            </summary>
            <div class="mt-1 mb-1 ml-3 pl-3 space-y-0.5 border-l-2 border-emerald-200/90 dark:border-emerald-800/60 pb-1">
                @if($hasFinanceiroMenu)
                <a href="{{ route('diretoria.financeiro.dashboard') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.financeiro.*') ? 'bg-emerald-50 text-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                    <x-module-icon module="Financeiro" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                    Tesouraria
                </a>
                @endif
                @if($hasGatewayMenu)
                <a href="{{ route('diretoria.gateway.dashboard') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.gateway.*') ? 'bg-emerald-50 text-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                    <x-icon name="credit-card" class="w-4 h-4 shrink-0 opacity-90 text-emerald-700 dark:text-emerald-300" style="duotone" />
                    Pagamentos (PSP)
                </a>
                @endif
                @if($hasCalendarioDiretoriaMenu)
                <a href="{{ route('diretoria.calendario.dashboard') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.calendario.*') ? 'bg-emerald-50 text-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                    <x-module-icon module="Calendario" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                    Calendário
                </a>
                @endif
                @if($hasTalentosMenu)
                <a href="{{ route('diretoria.talentos.dashboard') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.talentos.*') ? 'bg-emerald-50 text-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                    <x-module-icon module="Talentos" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                    Talentos
                </a>
                @endif
            </div>
        </details>
        @endif

        @if(user_is_diretoria_executive())
            @if(module_enabled('Bible') && Route::has('diretoria.bible.index'))
            <details class="diretoria-nav-details rounded-lg group" @if($bibleOpen) open @endif>
                <summary class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer select-none {{ $bibleOpen ? 'bg-amber-100 text-amber-900 dark:bg-amber-900/30 dark:text-amber-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700' }}">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ $bibleOpen ? 'bg-amber-600 dark:bg-amber-700' : 'bg-gray-100 dark:bg-slate-700' }}">
                        <x-icon name="book-bible" class="w-5 h-5 {{ $bibleOpen ? 'text-white' : 'text-gray-600 dark:text-gray-400' }}" style="duotone" />
                    </div>
                    <span class="flex-1 text-left">Bíblia digital</span>
                    <x-icon name="chevron-down" class="diretoria-nav-chevron w-4 h-4 shrink-0 text-amber-800/70 dark:text-amber-200/80 transition-transform duration-200" style="duotone" />
                </summary>
                <div class="mt-1 mb-1 ml-3 pl-3 space-y-3 border-l-2 border-amber-200/90 dark:border-amber-800/60">
                    <div class="space-y-0.5">
                        <p class="px-2 pt-1 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Texto e versões</p>
                        @if(Route::has('diretoria.bible.index'))
                        <a href="{{ route('diretoria.bible.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ $bibleVersionsActive ? 'bg-amber-50 text-amber-900 dark:bg-amber-950/50 dark:text-amber-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                            <x-icon name="book-open" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                            Versões e livros
                        </a>
                        @endif
                        @if(Route::has('diretoria.bible.create'))
                        <a href="{{ route('diretoria.bible.create') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.bible.create') ? 'bg-amber-50 text-amber-900 dark:bg-amber-950/50 dark:text-amber-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                            <x-icon name="plus" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                            Nova versão
                        </a>
                        @endif
                    </div>
                    @if(Route::has('diretoria.bible.import'))
                    <div class="space-y-0.5">
                        <p class="px-2 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Importação</p>
                        <a href="{{ route('diretoria.bible.import') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ $bibleImportActive ? 'bg-amber-50 text-amber-900 dark:bg-amber-950/50 dark:text-amber-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                            <x-icon name="file-import" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                            JSON
                        </a>
                    </div>
                    @endif
                    @if(Route::has('diretoria.bible.plans.index'))
                    <div class="space-y-0.5">
                        <p class="px-2 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Planos de leitura</p>
                        <a href="{{ route('diretoria.bible.plans.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ $biblePlansActive ? 'bg-amber-50 text-amber-900 dark:bg-amber-950/50 dark:text-amber-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                            <x-icon name="calendar-check" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                            Planos
                        </a>
                    </div>
                    @endif
                    @if(Route::has('diretoria.bible.study.strongs.index') || Route::has('diretoria.bible.study.commentary.sources.index') || Route::has('diretoria.bible.study.cross-refs.index'))
                    <div class="space-y-0.5">
                        <p class="px-2 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Estudo avançado</p>
                        @if(Route::has('diretoria.bible.study.strongs.index'))
                        <a href="{{ route('diretoria.bible.study.strongs.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ bible_route_is('diretoria.bible.study.strongs.*') ? 'bg-amber-50 text-amber-900 dark:bg-amber-950/50 dark:text-amber-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                            <x-icon name="book-bible" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                            Léxico Strong
                        </a>
                        @endif
                        @if(Route::has('diretoria.bible.study.commentary.sources.index'))
                        <a href="{{ route('diretoria.bible.study.commentary.sources.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ bible_route_is('diretoria.bible.study.commentary.sources.*') ? 'bg-amber-50 text-amber-900 dark:bg-amber-950/50 dark:text-amber-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                            <x-icon name="comments" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                            Comentários (fontes)
                        </a>
                        @endif
                        @if(Route::has('diretoria.bible.study.commentary.entries.index'))
                        <a href="{{ route('diretoria.bible.study.commentary.entries.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ bible_route_is('diretoria.bible.study.commentary.entries.*') ? 'bg-amber-50 text-amber-900 dark:bg-amber-950/50 dark:text-amber-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                            <x-icon name="pen-to-square" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                            Comentários (entradas)
                        </a>
                        @endif
                        @if(Route::has('diretoria.bible.study.cross-refs.index'))
                        <a href="{{ route('diretoria.bible.study.cross-refs.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ bible_route_is('diretoria.bible.study.cross-refs.*') ? 'bg-amber-50 text-amber-900 dark:bg-amber-950/50 dark:text-amber-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                            <x-icon name="link" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                            Refs. cruzadas
                        </a>
                        @endif
                    </div>
                    @endif
                    @if(Route::has('diretoria.bible.reports.church-plan'))
                    <div class="space-y-0.5 pb-1">
                        <p class="px-2 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Relatórios</p>
                        <a href="{{ route('diretoria.bible.reports.church-plan') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ $bibleReportsActive ? 'bg-amber-50 text-amber-900 dark:bg-amber-950/50 dark:text-amber-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                            <x-icon name="chart-line" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                            Plano por igreja
                        </a>
                    </div>
                    @endif
                </div>
            </details>
            @endif

            @if($adminGroupVisible)
            <details class="diretoria-nav-details rounded-lg group" @if($adminGroupOpen) open @endif>
                <summary class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer select-none {{ $adminGroupOpen ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900/30 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700' }}">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ $adminGroupOpen ? 'bg-indigo-600 dark:bg-indigo-700' : 'bg-gray-100 dark:bg-slate-700' }}">
                        <x-icon name="screwdriver-wrench" class="w-5 h-5 {{ $adminGroupOpen ? 'text-white' : 'text-gray-600 dark:text-gray-400' }}" style="duotone" />
                    </div>
                    <span class="flex-1 text-left">Plataforma</span>
                    <x-icon name="chevron-down" class="diretoria-nav-chevron w-4 h-4 shrink-0 text-indigo-800/70 dark:text-indigo-200/80 transition-transform duration-200" style="duotone" />
                </summary>
                <div class="mt-1 mb-1 ml-3 pl-3 space-y-0.5 border-l-2 border-indigo-200/90 dark:border-indigo-800/60 pb-1">
                    @if($hasAdminHub)
                    <a href="{{ route('diretoria.seguranca.hub') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.seguranca.*') ? 'bg-indigo-50 text-indigo-900 dark:bg-indigo-950/50 dark:text-indigo-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-icon name="shield-halved" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                        Hub segurança
                    </a>
                    @endif
                    @if($hasProfileDataRequestsMenu)
                    <a href="{{ route('diretoria.profile-data-requests.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.profile-data-requests.*') ? 'bg-indigo-50 text-indigo-900 dark:bg-indigo-950/50 dark:text-indigo-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-icon name="envelope-open-text" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                        Pedidos e-mail / CPF
                        @if($pendingProfileDataRequestsCount > 0)
                            <span class="ml-auto inline-flex min-w-[1.25rem] justify-center rounded-full bg-amber-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $pendingProfileDataRequestsCount }}</span>
                        @endif
                    </a>
                    @endif
                    @if($hasAdminUsers)
                    <a href="{{ route('diretoria.users.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.users.*') ? 'bg-indigo-50 text-indigo-900 dark:bg-indigo-950/50 dark:text-indigo-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-icon name="users" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                        Utilizadores
                    </a>
                    @endif
                    @if($hasAdminRoles)
                    <a href="{{ route('diretoria.roles.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.roles.*') ? 'bg-indigo-50 text-indigo-900 dark:bg-indigo-950/50 dark:text-indigo-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-icon name="user-shield" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                        Funções
                    </a>
                    @endif
                    @if($hasAdminPerms)
                    <a href="{{ route('diretoria.permissions.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.permissions.*') ? 'bg-indigo-50 text-indigo-900 dark:bg-indigo-950/50 dark:text-indigo-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-icon name="key" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                        Permissões
                    </a>
                    @endif
                    @if($hasAdminModules)
                    <a href="{{ route('diretoria.modules.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.modules.*') ? 'bg-indigo-50 text-indigo-900 dark:bg-indigo-950/50 dark:text-indigo-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                        <x-icon name="cube" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                        Módulos
                    </a>
                    @endif
                </div>
            </details>
            @endif
        @endif

        {{-- Comunicação: avisos, notificações, chat --}}
        @if($commsGroupVisible)
        <details class="diretoria-nav-details rounded-lg group" @if($commsGroupOpen) open @endif>
            <summary class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 cursor-pointer select-none {{ $commsGroupOpen ? 'bg-orange-100 text-orange-900 dark:bg-orange-900/30 dark:text-orange-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700' }}">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ $commsGroupOpen ? 'bg-orange-500 dark:bg-orange-600' : 'bg-gray-100 dark:bg-slate-700' }}">
                    <x-icon name="comments" class="w-5 h-5 {{ $commsGroupOpen ? 'text-white' : 'text-gray-600 dark:text-gray-400' }}" style="duotone" />
                </div>
                <span class="flex-1 text-left">Comunicação</span>
                <x-icon name="chevron-down" class="diretoria-nav-chevron w-4 h-4 shrink-0 text-orange-800/70 dark:text-orange-200/80 transition-transform duration-200" style="duotone" />
            </summary>
            <div class="mt-1 mb-1 ml-3 pl-3 space-y-0.5 border-l-2 border-orange-200/90 dark:border-orange-800/60 pb-1">
                @if($hasAvisosMenu)
                <a href="{{ route('diretoria.avisos.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.avisos.*') ? 'bg-orange-50 text-orange-900 dark:bg-orange-950/50 dark:text-orange-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                    <x-module-icon module="avisos" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                    Avisos
                </a>
                @endif
                @if($hasNotificacoesMenu)
                <a href="{{ route('diretoria.notificacoes.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.notificacoes.*') ? 'bg-orange-50 text-orange-900 dark:bg-orange-950/50 dark:text-orange-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                    <x-module-icon module="Notificacoes" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                    Notificações
                </a>
                @endif
                @if($hasChatMenu)
                <a href="{{ route('diretoria.chat.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.chat.*') ? 'bg-orange-50 text-orange-900 dark:bg-orange-950/50 dark:text-orange-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                    <x-module-icon module="Chat" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                    Chat
                </a>
                @endif
                @if($hasBlogMenu)
                <a href="{{ route('diretoria.blog.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-xs font-medium transition-colors {{ request()->routeIs('diretoria.blog.*') ? 'bg-orange-50 text-orange-900 dark:bg-orange-950/50 dark:text-orange-200' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-700' }}">
                    <x-icon name="newspaper" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                    Blog
                </a>
                @endif
            </div>
        </details>
        @endif

        @if(Route::has('diretoria.profile'))
        <a href="{{ route('diretoria.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 {{ request()->routeIs('diretoria.profile') ? 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-slate-700' }}">
            <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ request()->routeIs('diretoria.profile') ? 'bg-slate-500 dark:bg-slate-600' : 'bg-gray-100 dark:bg-slate-700' }}">
                <x-icon name="user" class="w-5 h-5 {{ request()->routeIs('diretoria.profile') ? 'text-white' : 'text-gray-600 dark:text-gray-400' }}" style="duotone" />
            </div>
            <span class="flex-1">Perfil</span>
        </a>
        @endif
    </div>
</aside>
