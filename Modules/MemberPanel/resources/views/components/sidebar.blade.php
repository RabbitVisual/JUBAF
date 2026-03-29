@php
    use App\Services\MemberPanelAccess;
    use Illuminate\Support\Facades\Storage;
    use Modules\Treasury\App\Models\TreasuryPermission;

    $canTreasuryPanel = MemberPanelAccess::canUseModule(auth()->user(), MemberPanelAccess::MODULE_TREASURY);
    $canChurchesPanel = MemberPanelAccess::canUseModule(auth()->user(), MemberPanelAccess::MODULE_CHURCHES);
    $canGovernancePanel = MemberPanelAccess::canUseModule(auth()->user(), MemberPanelAccess::MODULE_GOVERNANCE);
    $canCouncilPanel = MemberPanelAccess::canUseModule(auth()->user(), MemberPanelAccess::MODULE_COUNCIL);
    $canFieldPanel = MemberPanelAccess::canUseModule(auth()->user(), MemberPanelAccess::MODULE_FIELD);
    $canDelegationPanel = MemberPanelAccess::canUseModule(auth()->user(), MemberPanelAccess::MODULE_DELEGATION_UI);
    $hasTreasuryPermission = TreasuryPermission::where('user_id', auth()->id())->first();
    $isTreasuryActive = request()->routeIs('memberpanel.treasury.*');
    $isInstitutionalActive = request()->routeIs('memberpanel.governance.*')
        || request()->routeIs('memberpanel.council.*')
        || request()->routeIs('memberpanel.field.*');
@endphp

<style>[x-cloak] { display: none !important; }</style>

<!-- Sidebar Multi-Column Layout -->
<div
    data-tour="sidebar"
    class="flex h-screen overflow-hidden fixed left-0 top-0 z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <!-- Icon Sidebar (Left) -->
    <div
        class="flex h-screen w-16 flex-col justify-between border-e border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div>
            <!-- Logo -->
            <div class="inline-flex size-16 items-center justify-center border-b border-gray-100 dark:border-gray-700">
                <a href="{{ route('memberpanel.dashboard') }}" class="group">
                    <img src="{{ asset('storage/image/logo_icon.png') }}" alt="Logo"
                        class="size-10 object-contain transition-transform group-hover:scale-110 rounded-lg bg-gray-100 dark:bg-gray-700 p-1"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                    <span
                        class="hidden size-10 place-content-center rounded-lg bg-purple-100 dark:bg-purple-900 text-xs font-bold text-purple-600 dark:text-purple-400">
                        {{ strtoupper(substr(config('app.name', 'V'), 0, 1)) }}
                    </span>
                </a>
            </div>

            <!-- Navigation Icons -->
            <div class="border-t border-gray-100 dark:border-gray-700">
                <div class="px-2">
                    <div class="py-4">
                        <a href="{{ route('memberpanel.dashboard') }}"
                            class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('memberpanel.dashboard*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <x-icon name="gauge-high" class="size-5" />
                            <span
                                class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                                Dashboard
                            </span>
                        </a>
                    </div>

                    <ul class="space-y-1 border-t border-gray-100 dark:border-gray-700 pt-4">

                        @if ($canChurchesPanel)
                        <!-- Igrejas ASBAF (atalho → diretório) -->
                        <li>
                            <a href="{{ route('memberpanel.churches.index') }}"
                                class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('memberpanel.churches*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                <x-icon name="place-of-worship" class="size-5" />
                                <span
                                    class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                                    Igrejas ASBAF
                                </span>
                            </a>
                        </li>
                        @endif

                        @if ($canGovernancePanel)
                        <li>
                            <a href="{{ route('memberpanel.governance.dashboard') }}"
                                class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('memberpanel.governance.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                <x-icon name="gavel" class="size-5" />
                                <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">Governança</span>
                            </a>
                        </li>
                        @endif
                        @if ($canCouncilPanel)
                        <li>
                            <a href="{{ route('memberpanel.council.dashboard') }}"
                                class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('memberpanel.council.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                <x-icon name="people-group" class="size-5" />
                                <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">Conselho</span>
                            </a>
                        </li>
                        @endif
                        @if ($canFieldPanel)
                        <li>
                            <a href="{{ route('memberpanel.field.dashboard') }}"
                                class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('memberpanel.field.*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                <x-icon name="route" class="size-5" />
                                <span class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">Campo</span>
                            </a>
                        </li>
                        @endif

                        <!-- Eventos -->
                        <li>
                            <a href="{{ route('memberpanel.events.index') }}"
                                class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('memberpanel.events*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                <x-icon name="calendar-days" class="size-5" />
                                <span
                                    class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                                    Eventos
                                </span>
                            </a>
                        </li>





                        <!-- Bíblia Digital -->
                        <li>
                            <a href="{{ route('memberpanel.bible.index') }}"
                                class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('memberpanel.bible*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                <x-icon name="book-bible" class="size-5" />
                                <span
                                    class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                                    Bíblia Digital
                                </span>
                            </a>
                        </li>

                        <!-- Planos de Leitura -->
                        <li>
                            <a href="{{ route('member.bible.plans.index') }}"
                                class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('member.bible.plans*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                <x-icon name="book-open-reader" class="size-5" />
                                <span
                                    class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                                    Planos
                                </span>
                            </a>
                        </li>

                        <!-- Notificações -->
                        <li>
                            <a href="{{ route('memberpanel.notifications.index') }}"
                                class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('memberpanel.notifications*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                <x-icon name="bell" class="size-5" />
                                @php
                                    $unreadCount = \Modules\Notifications\App\Models\UserNotification::where(
                                        'user_id',
                                        Auth::id(),
                                    )
                                        ->where('is_read', false)
                                        ->count();
                                @endphp
                                @if ($unreadCount > 0)
                                    <span
                                        class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                                @endif
                                <span
                                    class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                                    Notificações
                                    @if ($unreadCount > 0)
                                        <span class="ml-1">({{ $unreadCount }})</span>
                                    @endif
                                </span>
                            </a>
                        </li>



                        {{-- Doações --}}
                        @php
                            $hasActiveGateways = \Modules\PaymentGateway\App\Models\PaymentGateway::active()
                                ->get()
                                ->filter(function ($gateway) {
                                    return $gateway->isConfigured();
                                })
                                ->isNotEmpty();
                        @endphp
                        @if ($hasActiveGateways)
                            <!-- Nova Doação -->
                            <li>
                                <a href="{{ route('memberpanel.donations.create') }}"
                                    class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('memberpanel.donations.create') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    <x-icon name="hand-holding-dollar" class="size-5" />
                                    <span
                                        class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                                        Fazer Doação
                                    </span>
                                </a>
                            </li>
                            <!-- Minhas Doações -->
                            <li>
                                <a href="{{ route('memberpanel.donations.index') }}"
                                    class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('memberpanel.donations.index') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    <x-icon name="file-invoice-dollar" class="size-5" />
                                    <span
                                        class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                                        Minhas Doações
                                    </span>
                                </a>
                            </li>

                        @endif

                        @if ($canDelegationPanel)
                            <li>
                                <a href="{{ route('memberpanel.panel-access.index') }}"
                                    class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ request()->routeIs('memberpanel.panel-access*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    <x-icon name="key" class="size-5" />
                                    <span
                                        class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                                        Acessos painel
                                    </span>
                                </a>
                            </li>
                        @endif

                        <!-- Tesouraria (se tiver permissão) -->
                        @if ($canTreasuryPanel)
                            <li>
                                <a href="{{ route('memberpanel.treasury.dashboard') }}"
                                    class="group relative flex justify-center rounded-sm px-2 py-1.5 {{ $isTreasuryActive ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    <x-icon name="building-columns" class="size-5" />
                                    <span
                                        class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                                        Tesouraria
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        @if(auth()->user()->hasAdminAccess())
        <div class="px-2 pb-2">
             <a href="{{ route('admin.dashboard') }}"
                class="group relative flex w-full justify-center rounded-lg px-2 py-1.5 text-sm text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40">
                <x-icon name="user-shield" class="size-5" />
                <span
                    class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                    Painel Admin
                </span>
            </a>
        </div>
        @endif

        <!-- Logout -->
        <div
            class="sticky inset-x-0 bottom-0 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="group relative flex w-full justify-center rounded-lg px-2 py-1.5 text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300">
                    <x-icon name="right-from-bracket" class="size-5" />
                    <span
                        class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded-sm bg-gray-900 dark:bg-gray-700 px-2 py-1.5 text-xs font-medium text-white whitespace-nowrap group-hover:visible z-50">
                        Sair
                    </span>
                </button>
            </form>
        </div>
    </div>

    <!-- Text Sidebar (Right) -->
    <div
        class="flex h-screen w-64 flex-col justify-between border-e border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div class="flex-1 overflow-y-auto px-4 py-6">
            <!-- User Info -->
            <div class="mb-6 pb-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <img class="h-12 w-12 rounded-full object-cover ring-2 ring-purple-500 dark:ring-purple-400"
                        src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="h-12 w-12 rounded-full bg-linear-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold text-lg ring-2 ring-purple-500 dark:ring-purple-400"
                        style="display: none;">
                        {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <ul class="mt-4 space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('memberpanel.dashboard') }}"
                        class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.dashboard*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <span class="flex items-center gap-2">
                             <span class="shrink-0">
                                <x-icon name="gauge-high" class="size-5" />
                             </span>
                             Dashboard
                        </span>
                    </a>
                </li>

                @if ($canChurchesPanel)
                <!-- Igrejas ASBAF / filiação -->
                <li x-data="{ open: {{ request()->routeIs('memberpanel.churches*') ? 'true' : 'false' }} }">
                    <div class="flex flex-col">
                        <button type="button" @click="open = !open"
                            class="flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.churches*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <span class="flex items-center gap-2">
                                <span class="shrink-0">
                                    <x-icon name="place-of-worship" class="size-5" />
                                </span>
                                Igrejas ASBAF
                            </span>
                            <span class="shrink-0 transition duration-300" :class="{ '-rotate-180': open }">
                                <x-icon name="chevron-down" class="size-5" />
                            </span>
                        </button>
                        <ul class="mt-2 space-y-1 px-4" x-show="open" x-cloak x-transition>
                            <li>
                                <a href="{{ route('memberpanel.churches.index') }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.churches.index') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    Diretório de igrejas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('memberpanel.churches.create') }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.churches.create') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    Cadastrar igreja local
                                </a>
                            </li>
                            @if (auth()->user()->church_id && auth()->user()->church)
                                <li>
                                    <a href="{{ route('memberpanel.churches.show', auth()->user()->church) }}"
                                        class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.churches.show') && (int) optional(request()->route('church'))?->getKey() === (int) auth()->user()->church_id ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                        Minha igreja local
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if($canGovernancePanel || $canCouncilPanel || $canFieldPanel)
                <li x-data="{ open: {{ $isInstitutionalActive ? 'true' : 'false' }} }">
                    <div class="flex flex-col">
                        <button type="button" @click="open = !open"
                            class="flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-sm font-medium {{ $isInstitutionalActive ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <span class="flex items-center gap-2">
                                <x-icon name="landmark" class="size-5 shrink-0" />
                                Institucional
                            </span>
                            <x-icon name="chevron-down" class="size-5 shrink-0 transition duration-300" ::class="{ '-rotate-180': open }" />
                        </button>
                        <ul class="mt-2 space-y-1 px-4" x-show="open" x-cloak x-transition>
                            @if($canGovernancePanel)
                            <li>
                                <a href="{{ route('memberpanel.governance.dashboard') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.governance.dashboard') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Governança — visão geral</a>
                            </li>
                            <li>
                                <a href="{{ route('memberpanel.governance.assemblies.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.governance.assemblies*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Assembleias</a>
                            </li>
                            <li>
                                <a href="{{ route('memberpanel.governance.communications.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.governance.communications*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Comunicados</a>
                            </li>
                            @endif
                            @if($canCouncilPanel)
                            <li>
                                <a href="{{ route('memberpanel.council.dashboard') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.council.dashboard') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Conselho — visão geral</a>
                            </li>
                            <li>
                                <a href="{{ route('memberpanel.council.members.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.council.members*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Conselho — membros</a>
                            </li>
                            <li>
                                <a href="{{ route('memberpanel.council.meetings.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.council.meetings*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Conselho — reuniões</a>
                            </li>
                            @endif
                            @if($canFieldPanel)
                            <li>
                                <a href="{{ route('memberpanel.field.dashboard') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.field.dashboard') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Campo — visão geral</a>
                            </li>
                            <li>
                                <a href="{{ route('memberpanel.field.visits.index') }}" class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.field.visits*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Visitas de campo</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @php
                    $isAdmin = auth()->user()->hasRole('admin') || auth()->user()->isAdmin();
                @endphp

                <!-- Perfil -->
                <li x-data="{ open: {{ request()->routeIs('memberpanel.profile*') ? 'true' : 'false' }} }">
                    <div
                        class="flex flex-col">
                        <button @click="open = !open"
                            class="flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.profile*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <span class="flex items-center gap-2">
                                <span class="shrink-0">
                                    <x-icon name="user-circle" class="size-5" />
                                </span>
                                Conta
                            </span>
                            <span class="shrink-0 transition duration-300" :class="{ '-rotate-180': open }">
                                <x-icon name="chevron-down" class="size-5" />
                            </span>
                        </button>
                        <ul class="mt-2 space-y-1 px-4" x-show="open" x-cloak x-transition>
                            <li>
                                <a href="{{ route('memberpanel.profile.show') }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.profile*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    Meu Perfil
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Minha Leitura (Bíblia) -->
                <li x-data="{ open: {{ request()->routeIs('member.bible.plans*') ? 'true' : 'false' }} }">
                    <div class="flex flex-col">
                        <button @click="open = !open"
                            class="flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('member.bible.plans*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <span class="flex items-center gap-2">
                                <span class="shrink-0">
                                    <x-icon name="book-open-reader" class="size-5" />
                                </span>
                                Planos de Leitura
                            </span>
                            <span class="shrink-0 transition duration-300" :class="{ '-rotate-180': open }">
                                <x-icon name="chevron-down" class="size-5" />
                            </span>
                        </button>
                        <ul class="mt-2 space-y-1 px-4" x-show="open" x-cloak x-transition>
                            <li>
                                <a href="{{ route('member.bible.plans.index') }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('member.bible.plans.index') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    Meus Planos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('member.bible.plans.catalog') }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('member.bible.plans.catalog') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    Explorar Planos
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Recursos -->
                <li x-data="{ open: {{ request()->routeIs('memberpanel.bible*') || request()->routeIs('memberpanel.notifications*') || request()->routeIs('memberpanel.preferences.notifications*') ? 'true' : 'false' }} }">
                    <div class="flex flex-col">
                        <button @click="open = !open"
                            class="flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.bible*') || request()->routeIs('memberpanel.notifications*') || request()->routeIs('memberpanel.preferences.notifications*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <span class="flex items-center gap-2">
                                <span class="shrink-0">
                                    <x-icon name="book-open" class="size-5" />
                                </span>
                                Recursos
                            </span>
                            <span class="shrink-0 transition duration-300" :class="{ '-rotate-180': open }">
                                <x-icon name="chevron-down" class="size-5" />
                            </span>
                        </button>
                        <ul class="mt-2 space-y-1 px-4" x-show="open" x-cloak x-transition>
                            <li>
                                <a href="{{ route('memberpanel.bible.index') }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.bible.index') || request()->routeIs('memberpanel.bible.read') || request()->routeIs('memberpanel.bible.book') || request()->routeIs('memberpanel.bible.chapter') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    Bíblia Digital
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('memberpanel.bible.interlinear') }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.bible.interlinear') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    Bíblia Interlinear
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('member.bible.plans.index') }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('member.bible.plans*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    Planos de Leitura
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('memberpanel.notifications.index') }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.notifications.index') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    Notificações
                                    @if ($unreadCount > 0)
                                        <span
                                            class="ml-auto inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-500 text-white min-w-6">
                                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('memberpanel.preferences.notifications.index') }}"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.preferences.notifications*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                    O que receber / Silenciar
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>


                {{-- Financeiro --}}
                @if ($hasActiveGateways)
                    <li x-data="{ open: {{ request()->routeIs('memberpanel.donations*') ? 'true' : 'false' }} }">
                        <div class="flex flex-col">
                            <button @click="open = !open"
                                class="flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.donations*') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                <span class="flex items-center gap-2">
                                     <span class="shrink-0">
                                        <x-icon name="hand-holding-dollar" class="size-5" />
                                    </span>
                                    Financeiro
                                </span>
                                <span class="shrink-0 transition duration-300" :class="{ '-rotate-180': open }">
                                    <x-icon name="chevron-down" class="size-5" />
                                </span>
                            </button>
                            <ul class="mt-2 space-y-1 px-4" x-show="open" x-cloak x-transition>
                                <li>
                                    <a href="{{ route('memberpanel.donations.create') }}"
                                        class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.donations.create') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                        Fazer Doação
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('memberpanel.donations.index') }}"
                                        class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.donations.index') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                        Minhas Doações
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if ($canDelegationPanel)
                    <li>
                        <a href="{{ route('memberpanel.panel-access.index') }}"
                            class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.panel-access*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <span class="flex items-center gap-2">
                                <x-icon name="key" class="size-5 shrink-0" />
                                Acessos ao painel
                            </span>
                        </a>
                    </li>
                @endif

                <!-- Tesouraria (se tiver permissão) -->
                @if ($canTreasuryPanel)
                    @php($treasuryCaps = TreasuryPermission::forUserOrAdmin(auth()->user()))
                    <li x-data="{ open: {{ $isTreasuryActive ? 'true' : 'false' }} }">
                        <div class="flex flex-col">
                            <button @click="open = !open"
                                class="flex w-full cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-sm font-medium {{ $isTreasuryActive ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                <span class="flex items-center gap-2">
                                <span class="shrink-0">
                                    <x-icon name="building-columns" class="size-5" />
                                </span>
                                Tesouraria
                            </span>
                                <span class="shrink-0 transition duration-300" :class="{ '-rotate-180': open }">
                                    <x-icon name="chevron-down" class="size-5" />
                                </span>
                            </button>
                            <ul class="mt-2 space-y-1 px-4" x-show="open" x-cloak x-transition>
                                <li>
                                    <a href="{{ route('memberpanel.treasury.dashboard') }}"
                                        class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.treasury.dashboard*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('memberpanel.treasury.transparency') }}"
                                        class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.treasury.transparency*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                        Transparência
                                    </a>
                                </li>

                                @if ($treasuryCaps->canCreateEntries() || $treasuryCaps->canEditEntries() || $treasuryCaps->canDeleteEntries())
                                    <li>
                                        <a href="{{ route('memberpanel.treasury.entries.index') }}"
                                            class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.treasury.entries*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                            Entradas Financeiras
                                        </a>
                                    </li>
                                @endif

                                @if ($treasuryCaps->canManageCampaigns())
                                    <li>
                                        <a href="{{ route('memberpanel.treasury.campaigns.index') }}"
                                            class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.treasury.campaigns*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                            Campanhas
                                        </a>
                                    </li>
                                @endif

                                @if ($treasuryCaps->canManageGoals())
                                    <li>
                                        <a href="{{ route('memberpanel.treasury.goals.index') }}"
                                            class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.treasury.goals*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                            Metas Financeiras
                                        </a>
                                    </li>
                                @endif

                                @if ($treasuryCaps->canViewReports())
                                    <li>
                                        <a href="{{ route('memberpanel.treasury.reports.index') }}"
                                            class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.treasury.reports*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                            Relatórios
                                        </a>
                                    </li>
                                @endif
                                @if ($treasuryCaps->isAdmin() || auth()->user()->canAccess('delegar_tesouraria'))
                                    <li>
                                        <a href="{{ route('memberpanel.treasury.permissions.index') }}"
                                            class="block rounded-lg px-4 py-2 text-sm font-medium {{ request()->routeIs('memberpanel.treasury.permissions*') ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 active' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300' }}">
                                            Permissões
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                @if(auth()->user()->hasAdminAccess())
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="block rounded-lg px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 border-t border-gray-100 dark:border-gray-700 mt-4 pt-4">
                        <span class="flex items-center gap-2">
                            <x-icon name="user-shield" class="size-5 shrink-0" />
                            Painel administrativo
                        </span>
                    </a>
                </li>
                @endif

                <!-- Voltar ao Site -->
                <li>
                    <a href="{{ route('homepage.index') }}"
                        class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-300 border-t border-gray-100 dark:border-gray-700 mt-4 pt-4">
                        Voltar ao Site
                    </a>
                </li>
            </ul>
        </div>

        <!-- Footer -->
        <div
            class="sticky inset-x-0 bottom-0 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
            <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                <p class="font-semibold">JUBAF</p>
                <p>© {{ date('Y') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Sidebar Overlay (Mobile) -->
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"></div>
