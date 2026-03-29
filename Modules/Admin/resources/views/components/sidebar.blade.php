@php
    $routes = [
        'treasury' => request()->routeIs('treasury.*'),
        'bible' => request()->routeIs('admin.bible*'),
        'homepage' => request()->routeIs('admin.homepage*'),
        'events' => request()->routeIs('admin.events*') || request()->routeIs('admin.events.checkin*'),
        'church' => request()->routeIs('admin.churches*') || request()->routeIs('admin.bible.reports.church-plan'),
        'institutional' => request()->routeIs('admin.governance.*') || request()->routeIs('admin.diretoria.*') || request()->routeIs('admin.council.*') || request()->routeIs('admin.field.*'),
    ];

    $hasTreasuryPermission = \Modules\Treasury\App\Models\TreasuryPermission::where('user_id', auth()->id())->first();
    $u = auth()->user();
    $showTreasury = \Nwidart\Modules\Facades\Module::isEnabled('Treasury')
        && ($u && ($u->isAdmin() || $u->canAccess('gerenciar_financeiro') || $hasTreasuryPermission));
@endphp

<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-72 bg-white dark:bg-slate-950 border-r border-gray-200 dark:border-gray-800 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-xl lg:shadow-none"
    x-data="{
        treasuryOpen: {{ $routes['treasury'] ? 'true' : 'false' }},
        bibleOpen: {{ $routes['bible'] ? 'true' : 'false' }},
        homepageOpen: {{ $routes['homepage'] ? 'true' : 'false' }},
        eventsOpen: {{ $routes['events'] ? 'true' : 'false' }},
        churchOpen: {{ $routes['church'] ? 'true' : 'false' }},
        institutionalOpen: {{ $routes['institutional'] ? 'true' : 'false' }},
    }">

    <div class="flex flex-col h-full">
        <!-- Brand Logo -->
        <div class="flex items-center h-16 px-6 border-b border-gray-100 dark:border-gray-800 bg-white dark:bg-slate-950">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group w-full">
                <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-linear-to-tr from-blue-600 to-indigo-600 shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/40 transition-all duration-300 group-hover:scale-105">
                     <img src="{{ asset('storage/image/logo_icon.png') }}" alt="Logo" class="w-7 h-7 object-contain" >
                </div>
                <div class="flex flex-col">
                    <span class="text-base font-bold text-gray-900 dark:text-gray-100 tracking-tight leading-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">JUBAF</span>
                    <span class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">Admin Panel</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto custom-scrollbar">



            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard*')
                    ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 shadow-sm'
                    : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <x-icon name="gauge-high" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400' }} transition-colors" />
                Dashboard
            </a>


            <div class="pt-4 pb-2">
                <p class="px-4 text-[11px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-wider">Sistema</p>
            </div>

            @if(auth()->user()->isAdmin())
                <!-- Modules -->
                <a href="{{ route('admin.modules.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.modules*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                    <x-icon name="cubes" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.modules*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                    Módulos
                </a>

                <!-- CEP Ranges -->
                <a href="{{ route('admin.cep-ranges.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.cep-ranges*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                    <x-icon name="map-location-dot" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.cep-ranges*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                    Gerenciar CEPs
                </a>

                <!-- Settings -->
                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.settings*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                    <x-icon name="gear" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.settings*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                    Configurações
                </a>
            @endif

            <div class="pt-4 pb-2">
                <p class="px-4 text-[11px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-wider">Gestão</p>
            </div>

            @if(auth()->user()->canAccessAny(['gerenciar_usuarios', 'ver_membros']))
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <x-icon name="users" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.users*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                Membros
            </a>
            @endif

            <div class="pt-2 pb-1">
                <p class="px-4 text-[10px] font-semibold text-gray-400 dark:text-gray-600 uppercase tracking-wider">Filiação &amp; Igrejas locais</p>
            </div>

            @if(auth()->user()->canAccess('gerenciar_igrejas'))
            <!-- Church / ASBAF (collapsible) -->
            <div class="space-y-1">
                <button type="button" @click="churchOpen = !churchOpen"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $routes['church'] ? 'bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                    <div class="flex items-center">
                        <x-icon name="place-of-worship" class="w-5 h-5 mr-3 {{ $routes['church'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                        <span>Igrejas ASBAF</span>
                    </div>
                    <x-icon name="chevron-down" class="w-4 h-4 transition-transform duration-200" ::class="{ 'rotate-180': churchOpen }" />
                </button>
                <div x-show="churchOpen" style="display: none;">
                    <div class="pl-12 pr-4 space-y-1 mt-1">
                        <a href="{{ route('admin.churches.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.churches.index') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="list" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Diretório de igrejas
                        </a>
                        <a href="{{ route('admin.churches.create') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.churches.create') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="plus" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Nova igreja local
                        </a>
                        <a href="{{ route('admin.bible.reports.church-plan') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.bible.reports.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="book-bible" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Planos de leitura por igreja
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if(auth()->user()->canAccessAny(['governance_manage', 'governance_view', 'council_manage', 'council_view', 'field_manage', 'field_view']))
            <div class="space-y-1">
                <button @click="institutionalOpen = !institutionalOpen"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $routes['institutional'] ? 'bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                    <div class="flex items-center">
                        <x-icon name="landmark" class="w-5 h-5 mr-3 {{ $routes['institutional'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                        <span>Institucional</span>
                    </div>
                    <x-icon name="chevron-down" class="w-4 h-4 transition-transform duration-200" ::class="{ 'rotate-180': institutionalOpen }" />
                </button>
                <div x-show="institutionalOpen" style="display: none;">
                    <div class="pl-12 pr-4 space-y-1 mt-1">
                        @if(auth()->user()->canAccessAny(['governance_manage', 'governance_view']))
                        <a href="{{ route('admin.governance.assemblies.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.governance.assemblies*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="gavel" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Assembleias
                        </a>
                        <a href="{{ route('admin.governance.communications.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.governance.communications*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="bullhorn" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Comunicados
                        </a>
                        @if(\Nwidart\Modules\Facades\Module::isEnabled('Diretoria'))
                        <a href="{{ route('admin.diretoria.minutes.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.diretoria.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="file-pdf" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Atas PDF (Diretoria)
                        </a>
                        @endif
                        @endif
                        @if(auth()->user()->canAccessAny(['council_manage', 'council_view']))
                        <a href="{{ route('admin.council.members.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.council.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="people-group" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Conselho de coordenação
                        </a>
                        @endif
                        @if(auth()->user()->canAccessAny(['field_manage', 'field_view']))
                        <a href="{{ route('admin.field.visits.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.field.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="route" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Campo / visitas
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @if(auth()->user()->canAccess('gerenciar_diretoria'))
            <!-- HomePage (Collapsible) -->
            <div class="space-y-1">
                <button @click="homepageOpen = !homepageOpen"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $routes['homepage'] ? 'bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                    <div class="flex items-center">
                        <x-icon name="house" class="w-5 h-5 mr-3 {{ $routes['homepage'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                        <span>HomePage</span>
                    </div>
                    <x-icon name="chevron-down" class="w-4 h-4 transition-transform duration-200" ::class="{ 'rotate-180': homepageOpen }" />
                </button>
                <div x-show="homepageOpen" style="display: none;">
                    <div class="pl-12 pr-4 space-y-1 mt-1">
                         <a href="{{ route('admin.homepage.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.homepage.index') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="pen-to-square" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Gerenciar Conteúdo
                        </a>
                        <a href="{{ route('admin.homepage.carousel.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.homepage.carousel*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="images" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Gerenciar Carousel
                        </a>
                        <a href="{{ route('admin.homepage.contacts.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.homepage.contacts*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="envelope" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Central de Contatos
                        </a>
                        <a href="{{ route('admin.homepage.newsletter.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.homepage.newsletter*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="newspaper" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Newsletter
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if(auth()->user()->canAccess('acesso_biblia'))
            <!-- Bible (Collapsible) -->
            <div class="space-y-1">
                <button @click="bibleOpen = !bibleOpen"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $routes['bible'] ? 'bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                    <div class="flex items-center">
                        <x-icon name="book-bible" class="w-5 h-5 mr-3 {{ $routes['bible'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                        <span>Bíblia Digital</span>
                    </div>
                    <x-icon name="chevron-down" class="w-4 h-4 transition-transform duration-200" ::class="{ 'rotate-180': bibleOpen }" />
                </button>
                <div x-show="bibleOpen" style="display: none;">
                    <div class="pl-12 pr-4 space-y-1 mt-1">
                        <a href="{{ route('admin.bible.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.bible.index') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="book-open" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Versões e Livros
                        </a>
                        <a href="{{ route('admin.bible.import') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.bible.import*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="file-import" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Importação
                        </a>
                        <a href="{{ route('admin.bible.plans.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.bible.plans.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="calendar-check" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Planos de Leitura
                        </a>
                        <a href="{{ route('admin.bible.reports.church-plan') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.bible.reports.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="chart-line" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Relatório Plano da Igreja
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if(auth()->user()->canAccessAny(['notificacoes_broadcast', 'gerenciar_usuarios']))
            <a href="{{ route('admin.notifications.index') }}"
                class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.notifications*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <x-icon name="bell" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.notifications*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                Notificações
            </a>
            @endif

            @if(auth()->user()->isAdmin())
                <!-- Password Resets Monitoring -->
                <a href="{{ route('admin.password-resets.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.password-resets*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                    <x-icon name="key" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.password-resets*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                    Monitoramento de Senhas
                </a>
            @endif

            <div class="pt-4 pb-2">
                <p class="px-4 text-[11px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-wider">Educação e Eventos</p>
            </div>












            <!-- Events (Collapsible) -->
            @can('viewAny', \Modules\Events\App\Models\Event::class)
             <div class="space-y-1">
                <button @click="eventsOpen = !eventsOpen"
                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $routes['events'] ? 'bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                    <div class="flex items-center">
                        <x-icon name="calendar-days" class="w-5 h-5 mr-3 {{ $routes['events'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                        <span>Eventos</span>
                    </div>
                    <x-icon name="chevron-down" class="w-4 h-4 transition-transform duration-200" ::class="{ 'rotate-180': eventsOpen }" />
                </button>
                <div x-show="eventsOpen" style="display: none;">
                    <div class="pl-12 pr-4 space-y-1 mt-1">
                        <a href="{{ route('admin.events.events.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.events.events.index') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="list" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Listar Eventos
                        </a>
                        <a href="{{ route('admin.events.events.create') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.events.events.create') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="plus" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Novo Evento
                        </a>
                        @can('checkin', \Modules\Events\App\Models\Event::class)
                        <a href="{{ route('admin.events.checkin.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.events.checkin.index') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                            <x-icon name="qrcode" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                            Check-in (Scanner)
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
            @endcan


            <!-- Financial Section -->
             @if(auth()->user()->canAccess('gerenciar_financeiro'))
            <div class="pt-4 pb-2">
                <p class="px-4 text-[11px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-wider">Financeiro</p>
            </div>

            <a href="{{ route('admin.transactions.index') }}"
                class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.transactions*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <x-icon name="money-bill-transfer" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.transactions*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                Transações
            </a>

            <a href="{{ route('admin.payment-gateways.index') }}"
                class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.payment-gateways*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                <x-icon name="credit-card" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.payment-gateways*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                Gateways
            </a>
            @endif

            @if ($showTreasury)
                <div class="space-y-1">
                    <button @click="treasuryOpen = !treasuryOpen" data-treasury-toggle
                        class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ $routes['treasury'] ? 'bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200' }}">
                        <div class="flex items-center">
                            <x-icon name="building-columns" class="w-5 h-5 mr-3 {{ $routes['treasury'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-500' }} transition-colors" />
                            <span>Tesouraria</span>
                        </div>
                        <x-icon name="chevron-down" class="w-4 h-4 transition-transform duration-200" ::class="{ 'rotate-180': treasuryOpen }" />
                    </button>
                     <div x-show="treasuryOpen" style="display: none;">
                        <div class="pl-12 pr-4 space-y-1 mt-1" data-treasury-menu>
                             <a href="{{ route('treasury.dashboard.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('treasury.dashboard*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                                <x-icon name="chart-pie" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                                Dashboard
                            </a>
                            <a href="{{ route('treasury.entries.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('treasury.entries*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                                <x-icon name="money-bill-transfer" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                                Entradas
                            </a>
                            <a href="{{ route('treasury.campaigns.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('treasury.campaigns*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                                <x-icon name="bullhorn" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                                Campanhas
                            </a>
                            <a href="{{ route('treasury.goals.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('treasury.goals*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                                <x-icon name="bullseye" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                                Metas
                            </a>
                            <a href="{{ route('treasury.reports.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('treasury.reports*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                                <x-icon name="chart-line" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                                Relatórios
                            </a>
                            @if (auth()->user()->isAdmin() || auth()->user()->canAccess('delegar_tesouraria') || ($hasTreasuryPermission && $hasTreasuryPermission->isAdmin()))
                                <a href="{{ route('treasury.permissions.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('treasury.permissions*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-900/10' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300' }}">
                                    <x-icon name="key" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                                    Permissões
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bottom Section -->
            <div class="mt-8 pt-4 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ route('memberpanel.dashboard') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200 transition-all duration-200 group">
                    <x-icon name="arrow-left" class="w-5 h-5 mr-3 text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors" />
                    Painel de Membros
                </a>
            </div>


            <!-- Copyright -->
            <div class="px-6 py-4 mt-auto">
                <p class="text-[9px] text-center text-gray-400 dark:text-gray-600 font-bold uppercase tracking-widest">
                    JUBAF © {{ date('Y') }}
                </p>
            </div>

        </nav>
    </div>
</aside>

<!-- Sidebar Overlay (Mobile) -->
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-30 lg:hidden transition-opacity" style="z-index: 30;"></div>
