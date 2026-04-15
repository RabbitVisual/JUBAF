@php
    use Modules\Igrejas\App\Models\Church;
    use Modules\Secretaria\App\Models\Meeting;

    $hasChurchesStat = $quickStats['churches_total'] !== null;
    $hasFinanceStat = ($quickStats['finance_month_balance'] ?? null) !== null;
    $hasCalendarStat = ($quickStats['calendar_upcoming'] ?? null) !== null;
    $hasTalentStat =
        ($quickStats['talent_profiles'] ?? null) !== null || ($quickStats['talent_assignments'] ?? null) !== null;

    $siteLinks = collect([
        module_enabled('Homepage') && Route::has('diretoria.homepage.index') && auth()->user()?->can('homepage.edit')
            ? [
                'route' => 'diretoria.homepage.index',
                'label' => 'Homepage',
                'desc' => 'Secções da página inicial',
                'icon' => 'module',
                'module' => 'Homepage',
                'accent' => 'blue',
            ]
            : null,
        user_is_diretoria_executive() && Route::has('diretoria.carousel.index')
            ? [
                'route' => 'diretoria.carousel.index',
                'label' => 'Carrossel',
                'desc' => 'Imagens do topo do site',
                'icon' => 'fa',
                'name' => 'photo',
                'accent' => 'blue',
            ]
            : null,
        module_enabled('Homepage') &&
        Route::has('diretoria.board-members.index') &&
        auth()->user()?->can('board_members.view')
            ? [
                'route' => 'diretoria.board-members.index',
                'label' => 'Membros da diretoria',
                'desc' => 'Equipa na página pública',
                'icon' => 'fa',
                'name' => 'users',
                'accent' => 'blue',
            ]
            : null,
        module_enabled('Homepage') &&
        Route::has('diretoria.devotionals.index') &&
        auth()->user()?->can('devotionals.view')
            ? [
                'route' => 'diretoria.devotionals.index',
                'label' => 'Devocionais',
                'desc' => 'Conteúdo espiritual',
                'icon' => 'fa',
                'name' => 'book-open',
                'accent' => 'blue',
            ]
            : null,
    ])
        ->filter()
        ->values();

    $orgLinks = collect([
        module_enabled('Igrejas') &&
        Route::has('diretoria.igrejas.dashboard') &&
        auth()->user()?->can('viewAny', Church::class)
            ? [
                'route' => 'diretoria.igrejas.dashboard',
                'label' => 'Igrejas',
                'desc' => 'Resumo e cadastro de congregações',
                'icon' => 'module',
                'module' => 'Igrejas',
                'accent' => 'cyan',
            ]
            : null,
        module_enabled('Secretaria') &&
        Route::has('diretoria.secretaria.dashboard') &&
        auth()->user()?->can('viewAny', Meeting::class)
            ? [
                'route' => 'diretoria.secretaria.dashboard',
                'label' => 'Secretaria',
                'desc' => 'Reuniões, atas e convocatórias',
                'icon' => 'module',
                'module' => 'Secretaria',
                'accent' => 'cyan',
            ]
            : null,
    ])
        ->filter()
        ->values();

    $planLinks = collect([
        module_enabled('Financeiro') &&
        Route::has('diretoria.financeiro.dashboard') &&
        auth()->user()?->can('financeiro.dashboard.view')
            ? [
                'route' => 'diretoria.financeiro.dashboard',
                'label' => 'Tesouraria',
                'desc' => 'Lançamentos, reembolsos e relatórios',
                'icon' => 'module',
                'module' => 'Financeiro',
                'accent' => 'emerald',
            ]
            : null,
        module_enabled('Calendario') &&
        Route::has('diretoria.calendario.dashboard') &&
        auth()->user()?->can('viewAny', \Modules\Calendario\App\Models\CalendarEvent::class)
            ? [
                'route' => 'diretoria.calendario.dashboard',
                'label' => 'Calendário',
                'desc' => 'Resumo, eventos e inscrições',
                'icon' => 'module',
                'module' => 'Calendario',
                'accent' => 'teal',
            ]
            : null,
        module_enabled('Talentos') &&
        Route::has('diretoria.talentos.dashboard') &&
        auth()->user() &&
        (auth()->user()->can('talentos.directory.view') || auth()->user()->can('talentos.assignments.view'))
            ? [
                'route' => 'diretoria.talentos.dashboard',
                'label' => 'Talentos',
                'desc' => 'Resumo, diretório e atribuições a eventos',
                'icon' => 'module',
                'module' => 'Talentos',
                'accent' => 'violet',
            ]
            : null,
    ])
        ->filter()
        ->values();

    $commsLinks = collect([
        module_enabled('Avisos') && Route::has('diretoria.avisos.index') && auth()->user()?->can('avisos.view')
            ? [
                'route' => 'diretoria.avisos.index',
                'label' => 'Avisos',
                'desc' => 'Banners e comunicados',
                'icon' => 'module',
                'module' => 'avisos',
                'accent' => 'orange',
            ]
            : null,
        module_enabled('Notificacoes') && Route::has('diretoria.notificacoes.index')
            ? [
                'route' => 'diretoria.notificacoes.index',
                'label' => 'Notificações',
                'desc' => 'Mensagens à plataforma',
                'icon' => 'module',
                'module' => 'Notificacoes',
                'accent' => 'orange',
            ]
            : null,
        module_enabled('Chat') && Route::has('diretoria.chat.index')
            ? [
                'route' => 'diretoria.chat.index',
                'label' => 'Chat',
                'desc' => 'Atendimento e conversas',
                'icon' => 'module',
                'module' => 'Chat',
                'accent' => 'orange',
            ]
            : null,
    ])
        ->filter()
        ->values();

    $platformLinks = collect([
        user_is_diretoria_executive() && Route::has('diretoria.users.index')
            ? [
                'route' => 'diretoria.users.index',
                'label' => 'Utilizadores',
                'desc' => 'Contas e acesso',
                'icon' => 'fa',
                'name' => 'users',
                'accent' => 'indigo',
            ]
            : null,
        user_is_diretoria_executive() && Route::has('diretoria.roles.index')
            ? [
                'route' => 'diretoria.roles.index',
                'label' => 'Funções',
                'desc' => 'Papéis e cargos',
                'icon' => 'fa',
                'name' => 'user-shield',
                'accent' => 'indigo',
            ]
            : null,
        user_is_diretoria_executive() && Route::has('diretoria.permissions.index')
            ? [
                'route' => 'diretoria.permissions.index',
                'label' => 'Permissões',
                'desc' => 'Matriz de permissões',
                'icon' => 'fa',
                'name' => 'key',
                'accent' => 'indigo',
            ]
            : null,
        user_is_diretoria_executive() && Route::has('diretoria.modules.index')
            ? [
                'route' => 'diretoria.modules.index',
                'label' => 'Módulos',
                'desc' => 'Ativar ou desativar módulos',
                'icon' => 'fa',
                'name' => 'cube',
                'accent' => 'indigo',
            ]
            : null,
        user_is_diretoria_executive() && module_enabled('Bible') && Route::has('diretoria.bible.index')
            ? [
                'route' => 'diretoria.bible.index',
                'label' => 'Bíblia digital',
                'desc' => 'Versões, planos e estudo',
                'icon' => 'fa',
                'name' => 'book-bible',
                'accent' => 'amber',
            ]
            : null,
    ])
        ->filter()
        ->values();

    $accentRing = [
        'blue' => 'ring-blue-500/20 dark:ring-blue-400/20',
        'cyan' => 'ring-cyan-500/20 dark:ring-cyan-400/20',
        'orange' => 'ring-orange-500/20 dark:ring-orange-400/20',
        'indigo' => 'ring-indigo-500/20 dark:ring-indigo-400/20',
        'amber' => 'ring-amber-500/25 dark:ring-amber-400/20',
        'emerald' => 'ring-emerald-500/20 dark:ring-emerald-400/20',
        'teal' => 'ring-teal-500/20 dark:ring-teal-400/20',
        'violet' => 'ring-violet-500/20 dark:ring-violet-400/20',
    ];
    $accentIconBg = [
        'blue' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
        'cyan' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300',
        'orange' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-200',
        'indigo' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300',
        'amber' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
        'emerald' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
        'teal' => 'bg-teal-100 text-teal-800 dark:bg-teal-900/40 dark:text-teal-200',
        'violet' => 'bg-violet-100 text-violet-800 dark:bg-violet-900/40 dark:text-violet-200',
    ];

    $statCount =
        1 +
        2 +
        ($hasChurchesStat ? 1 : 0) +
        ($hasFinanceStat ? 1 : 0) +
        ($hasCalendarStat ? 1 : 0) +
        ($hasTalentStat ? 1 : 0);
    $statGridClass =
        $statCount >= 4
            ? 'grid-cols-1 sm:grid-cols-2 xl:grid-cols-4'
            : ($statCount === 3
                ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3'
                : 'grid-cols-1 sm:grid-cols-2');

    $platformHoverBorder = [
        'indigo' => 'hover:border-indigo-300 dark:hover:border-indigo-500',
        'amber' => 'hover:border-amber-300 dark:hover:border-amber-600',
    ];
    $platformFocusRing = [
        'indigo' => 'focus-visible:ring-indigo-500',
        'amber' => 'focus-visible:ring-amber-500',
    ];
    $platformTitleHover = [
        'indigo' => 'group-hover:text-indigo-700 dark:group-hover:text-indigo-300',
        'amber' => 'group-hover:text-amber-800 dark:group-hover:text-amber-200',
    ];
    $platformChevronHover = [
        'indigo' => 'group-hover:text-indigo-500',
        'amber' => 'group-hover:text-amber-500',
    ];

    $planHoverBorder = [
        'emerald' => 'hover:border-emerald-300 dark:hover:border-emerald-600',
        'teal' => 'hover:border-teal-300 dark:hover:border-teal-600',
        'violet' => 'hover:border-violet-300 dark:hover:border-violet-600',
    ];
    $planFocusRing = [
        'emerald' => 'focus-visible:ring-emerald-500',
        'teal' => 'focus-visible:ring-teal-500',
        'violet' => 'focus-visible:ring-violet-500',
    ];
    $planTitleHover = [
        'emerald' => 'group-hover:text-emerald-800 dark:group-hover:text-emerald-200',
        'teal' => 'group-hover:text-teal-800 dark:group-hover:text-teal-200',
        'violet' => 'group-hover:text-violet-800 dark:group-hover:text-violet-200',
    ];
    $planChevronHover = [
        'emerald' => 'group-hover:text-emerald-500',
        'teal' => 'group-hover:text-teal-500',
        'violet' => 'group-hover:text-violet-500',
    ];
@endphp

@extends('paineldiretoria::components.layouts.app')

@section('title', 'Painel da Diretoria')

@section('content')
    <div class="space-y-8 md:space-y-10 max-w-7xl mx-auto animate-fade-in pb-8">
        {{-- Hero: perfil e boas-vindas --}}
        <div
            class="relative overflow-hidden rounded-3xl border border-gray-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/[0.07] via-violet-500/[0.04] to-transparent pointer-events-none"
                aria-hidden="true"></div>
            <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-indigo-400/10 dark:bg-indigo-500/10 blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"
                aria-hidden="true"></div>

            <div class="relative p-6 sm:p-8 lg:p-10">
                <div class="flex flex-col lg:flex-row lg:items-center gap-8 lg:gap-10">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-6 flex-1 min-w-0">
                        <div class="relative shrink-0 mx-auto sm:mx-0">
                            <div
                                class="w-28 h-28 sm:w-32 sm:h-32 rounded-[1.75rem] border-4 border-white dark:border-slate-800 shadow-xl ring-2 ring-indigo-500/15 dark:ring-indigo-400/20 overflow-hidden bg-gradient-to-br from-indigo-100 to-violet-100 dark:from-indigo-950/50 dark:to-slate-800">
                                @if (user_photo_url($user))
                                    <img src="{{ user_photo_url($user) }}" alt=""
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center text-3xl sm:text-4xl font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            @if ($user->active ?? true)
                                <span
                                    class="absolute bottom-1 right-1 flex h-6 w-6 items-center justify-center rounded-xl border-4 border-white dark:border-slate-800 bg-emerald-500 shadow-sm"
                                    title="Conta ativa">
                                    <x-icon name="check" class="h-3 w-3 text-white" style="solid" />
                                </span>
                            @endif
                        </div>

                        <div class="min-w-0 text-center sm:text-left">
                            <p
                                class="text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 mb-1">
                                JUBAF · Diretoria regional</p>
                            <h1
                                class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                                Olá, <span
                                    class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-400 dark:to-violet-400">{{ $user->name }}</span>
                            </h1>
                            @if ($user->email)
                                <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400 truncate">{{ $user->email }}</p>
                            @endif
                            @if ($user->church)
                                <p class="mt-1 inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                                    <x-icon name="church" class="h-4 w-4 shrink-0 opacity-70" style="duotone" />
                                    <span class="truncate">{{ $user->church->name }}</span>
                                </p>
                            @endif

                            <div class="mt-4 flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                @forelse($user->roles as $role)
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-indigo-50 text-indigo-800 ring-1 ring-inset ring-indigo-600/10 dark:bg-indigo-950/50 dark:text-indigo-200 dark:ring-indigo-400/20">
                                        {{ jubaf_role_label($role->name) }}
                                    </span>
                                @empty
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">Sem
                                        função atribuída</span>
                                @endforelse
                                @if (user_is_diretoria_executive())
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide bg-violet-100 text-violet-900 dark:bg-violet-900/40 dark:text-violet-200 ring-1 ring-violet-600/15 dark:ring-violet-400/25">
                                        <x-icon name="star" class="h-3.5 w-3.5" style="duotone" />
                                        Executivo
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row lg:flex-col xl:flex-row gap-3 shrink-0 justify-center lg:justify-end">
                        @if (Route::has('diretoria.profile'))
                            <a href="{{ route('diretoria.profile') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 hover:from-indigo-500 hover:to-violet-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-800 transition-all">
                                <x-icon name="user-gear" class="h-5 w-5" style="duotone" />
                                Meu perfil
                            </a>
                        @endif
                        <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-200 dark:border-slate-600 bg-white/80 dark:bg-slate-800/80 px-5 py-3 text-sm font-semibold text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                            <x-icon name="arrow-up-right-from-square" class="h-4 w-4" style="duotone" />
                            Ver site público
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Indicadores --}}
        <div>
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-4">Resumo</h2>
            <div class="grid {{ $statGridClass }} gap-4">
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-200/90 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Utilizadores</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                                {{ $quickStats['users_total'] }}</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Contas registadas na plataforma</p>
                        </div>
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 ring-1 ring-indigo-500/10">
                            <x-icon name="users" class="h-6 w-6" style="duotone" />
                        </div>
                    </div>
                    @if (user_is_diretoria_executive() && Route::has('diretoria.users.index'))
                        <a href="{{ route('diretoria.users.index') }}"
                            class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:gap-2 transition-all">
                            Gerir <x-icon name="arrow-right" class="h-3.5 w-3.5" style="duotone" />
                        </a>
                    @endif
                </div>

                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-200/90 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Diretoria
                                (site)</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                                {{ $quickStats['board_members'] }}</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Membros na página pública</p>
                        </div>
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 ring-1 ring-blue-500/10">
                            <x-icon name="users" class="h-6 w-6" style="duotone" />
                        </div>
                    </div>
                    @if (module_enabled('Homepage') &&
                            Route::has('diretoria.board-members.index') &&
                            auth()->user()?->can('board_members.view'))
                        <a href="{{ route('diretoria.board-members.index') }}"
                            class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400 hover:gap-2 transition-all">
                            Editar equipe <x-icon name="arrow-right" class="h-3.5 w-3.5" style="duotone" />
                        </a>
                    @endif
                </div>

                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-200/90 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Devocionais</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                                {{ $quickStats['devotionals_published'] ?? '—' }}</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Publicados no site</p>
                        </div>
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300 ring-1 ring-violet-500/10">
                            <x-icon name="book-open" class="h-6 w-6" style="duotone" />
                        </div>
                    </div>
                    @if (module_enabled('Homepage') && Route::has('diretoria.devotionals.index') && auth()->user()?->can('devotionals.view'))
                        <a href="{{ route('diretoria.devotionals.index') }}"
                            class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-violet-600 dark:text-violet-400 hover:gap-2 transition-all">
                            Gerir <x-icon name="arrow-right" class="h-3.5 w-3.5" style="duotone" />
                        </a>
                    @endif
                </div>

                @if ($hasFinanceStat)
                    <div
                        class="group relative overflow-hidden rounded-2xl border border-gray-200/90 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Saldo
                                    (mês)</p>
                                <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">R$
                                    {{ number_format((float) $quickStats['finance_month_balance'], 2, ',', '.') }}</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Receitas menos despesas no mês
                                    corrente</p>
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 ring-1 ring-emerald-500/10">
                                <x-module-icon module="Financeiro" class="h-7 w-7" style="duotone" />
                            </div>
                        </div>
                        @if (Route::has('diretoria.financeiro.dashboard'))
                            <a href="{{ route('diretoria.financeiro.dashboard') }}"
                                class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-emerald-600 dark:text-emerald-400 hover:gap-2 transition-all">
                                Abrir tesouraria <x-icon name="arrow-right" class="h-3.5 w-3.5" style="duotone" />
                            </a>
                        @endif
                    </div>
                @endif

                @if ($hasCalendarStat)
                    <div
                        class="group relative overflow-hidden rounded-2xl border border-gray-200/90 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Eventos (60 dias)</p>
                                <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                                    {{ $quickStats['calendar_upcoming'] }}</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Com data de início nos próximos
                                    dois meses</p>
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-100 text-teal-700 dark:bg-teal-900/50 dark:text-teal-300 ring-1 ring-teal-500/10">
                                <x-module-icon module="Calendario" class="h-7 w-7" style="duotone" />
                            </div>
                        </div>
                        @if (Route::has('diretoria.calendario.dashboard'))
                            <a href="{{ route('diretoria.calendario.dashboard') }}"
                                class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-teal-600 dark:text-teal-400 hover:gap-2 transition-all">
                                Gerir calendário <x-icon name="arrow-right" class="h-3.5 w-3.5" style="duotone" />
                            </a>
                        @endif
                    </div>
                @endif

                @if ($hasTalentStat)
                    <div
                        class="group relative overflow-hidden rounded-2xl border border-gray-200/90 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                @if (($quickStats['talent_profiles'] ?? null) !== null)
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Perfis de talentos</p>
                                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                                        {{ $quickStats['talent_profiles'] }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Membros com ficha no módulo
                                        Talentos</p>
                                    @if (($quickStats['talent_assignments'] ?? null) !== null)
                                        <p class="mt-2 text-xs font-medium text-violet-700 dark:text-violet-400">
                                            Atribuições registadas: {{ $quickStats['talent_assignments'] }}</p>
                                    @endif
                                @else
                                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        Atribuições de talentos</p>
                                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                                        {{ $quickStats['talent_assignments'] }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Convites e funções no módulo
                                    </p>
                                @endif
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300 ring-1 ring-violet-500/10">
                                <x-module-icon module="Talentos" class="h-7 w-7" style="duotone" />
                            </div>
                        </div>
                        @if (Route::has('diretoria.talentos.dashboard'))
                            <a href="{{ route('diretoria.talentos.dashboard') }}"
                                class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-violet-600 dark:text-violet-400 hover:gap-2 transition-all">
                                Abrir talentos <x-icon name="arrow-right" class="h-3.5 w-3.5" style="duotone" />
                            </a>
                        @endif
                    </div>
                @endif

                @if ($hasChurchesStat)
                    <div
                        class="group relative overflow-hidden rounded-2xl border border-gray-200/90 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Congregações</p>
                                <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">
                                    {{ $quickStats['churches_total'] }}</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Igrejas no cadastro associacional (ASBAF)</p>
                            </div>
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300 ring-1 ring-cyan-500/10">
                                <x-module-icon module="Igrejas" class="h-7 w-7" style="duotone" />
                            </div>
                        </div>
                        @if (Route::has('diretoria.igrejas.dashboard'))
                            <a href="{{ route('diretoria.igrejas.dashboard') }}"
                                class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-cyan-600 dark:text-cyan-400 hover:gap-2 transition-all">
                                Abrir igrejas <x-icon name="arrow-right" class="h-3.5 w-3.5" style="duotone" />
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Acesso rápido por área --}}
        <div class="space-y-8">
            <div
                class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 border-b border-gray-200 dark:border-slate-700 pb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Acesso rápido</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Atalhos alinhados com o menu lateral — escolha
                        a área de trabalho.</p>
                </div>
            </div>

            @if ($siteLinks->isNotEmpty())
                <section aria-labelledby="dash-site-heading">
                    <h3 id="dash-site-heading"
                        class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                        <span class="h-4 w-1 rounded-full bg-blue-500" aria-hidden="true"></span>
                        Site público
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($siteLinks as $link)
                            <a href="{{ route($link['route']) }}"
                                class="group flex gap-4 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-md transition-all ring-1 ring-transparent {{ $accentRing[$link['accent']] }} focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $accentIconBg[$link['accent']] }}">
                                    @if ($link['icon'] === 'module')
                                        <x-module-icon :module="$link['module']" class="h-7 w-7" style="duotone" />
                                    @else
                                        <x-icon :name="$link['name']" class="h-7 w-7" style="duotone" />
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors">{{ $link['label'] }}</span>
                                        <x-icon name="chevron-right"
                                            class="h-4 w-4 text-gray-400 group-hover:text-blue-500 group-hover:translate-x-0.5 transition-transform shrink-0"
                                            style="duotone" />
                                    </div>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 line-clamp-2">
                                        {{ $link['desc'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($orgLinks->isNotEmpty())
                <section aria-labelledby="dash-org-heading">
                    <h3 id="dash-org-heading"
                        class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                        <span class="h-4 w-1 rounded-full bg-cyan-500" aria-hidden="true"></span>
                        Organização
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($orgLinks as $link)
                            <a href="{{ route($link['route']) }}"
                                class="group flex gap-4 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm hover:border-cyan-300 dark:hover:border-cyan-600 hover:shadow-md transition-all ring-1 ring-transparent {{ $accentRing[$link['accent']] }} focus:outline-none focus-visible:ring-2 focus-visible:ring-cyan-500">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $accentIconBg[$link['accent']] }}">
                                    <x-module-icon :module="$link['module']" class="h-7 w-7" style="duotone" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white group-hover:text-cyan-800 dark:group-hover:text-cyan-200 transition-colors">{{ $link['label'] }}</span>
                                        <x-icon name="chevron-right"
                                            class="h-4 w-4 text-gray-400 group-hover:text-cyan-500 group-hover:translate-x-0.5 transition-transform shrink-0"
                                            style="duotone" />
                                    </div>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $link['desc'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($planLinks->isNotEmpty())
                <section aria-labelledby="dash-plan-heading">
                    <h3 id="dash-plan-heading"
                        class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                        <span class="h-4 w-1 rounded-full bg-emerald-500" aria-hidden="true"></span>
                        Tesouraria, calendário e talentos
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($planLinks as $link)
                            <a href="{{ route($link['route']) }}"
                                class="group flex gap-4 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm {{ $planHoverBorder[$link['accent']] }} hover:shadow-md transition-all ring-1 ring-transparent {{ $accentRing[$link['accent']] }} focus:outline-none focus-visible:ring-2 {{ $planFocusRing[$link['accent']] }}">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $accentIconBg[$link['accent']] }}">
                                    <x-module-icon :module="$link['module']" class="h-7 w-7" style="duotone" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white {{ $planTitleHover[$link['accent']] }} transition-colors">{{ $link['label'] }}</span>
                                        <x-icon name="chevron-right"
                                            class="h-4 w-4 text-gray-400 {{ $planChevronHover[$link['accent']] }} group-hover:translate-x-0.5 transition-transform shrink-0"
                                            style="duotone" />
                                    </div>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $link['desc'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($commsLinks->isNotEmpty())
                <section aria-labelledby="dash-comms-heading">
                    <h3 id="dash-comms-heading"
                        class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                        <span class="h-4 w-1 rounded-full bg-orange-500" aria-hidden="true"></span>
                        Comunicação
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($commsLinks as $link)
                            <a href="{{ route($link['route']) }}"
                                class="group flex gap-4 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm hover:border-orange-300 dark:hover:border-orange-600 hover:shadow-md transition-all ring-1 ring-transparent {{ $accentRing[$link['accent']] }} focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $accentIconBg[$link['accent']] }}">
                                    <x-module-icon :module="$link['module']" class="h-7 w-7" style="duotone" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white group-hover:text-orange-800 dark:group-hover:text-orange-200 transition-colors">{{ $link['label'] }}</span>
                                        <x-icon name="chevron-right"
                                            class="h-4 w-4 text-gray-400 group-hover:text-orange-500 group-hover:translate-x-0.5 transition-transform shrink-0"
                                            style="duotone" />
                                    </div>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $link['desc'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($platformLinks->isNotEmpty())
                <section aria-labelledby="dash-platform-heading">
                    <h3 id="dash-platform-heading"
                        class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                        <span class="h-4 w-1 rounded-full bg-indigo-500" aria-hidden="true"></span>
                        Plataforma &amp; Bíblia
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($platformLinks as $link)
                            <a href="{{ route($link['route']) }}"
                                class="group flex gap-4 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm hover:shadow-md transition-all ring-1 ring-transparent {{ $accentRing[$link['accent']] }} {{ $platformHoverBorder[$link['accent']] }} focus:outline-none focus-visible:ring-2 {{ $platformFocusRing[$link['accent']] }}">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $accentIconBg[$link['accent']] }}">
                                    @if ($link['icon'] === 'module')
                                        <x-module-icon :module="$link['module']" class="h-7 w-7" style="duotone" />
                                    @else
                                        <x-icon :name="$link['name']" class="h-7 w-7" style="duotone" />
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white {{ $platformTitleHover[$link['accent']] }} transition-colors">{{ $link['label'] }}</span>
                                        <x-icon name="chevron-right"
                                            class="h-4 w-4 text-gray-400 {{ $platformChevronHover[$link['accent']] }} group-hover:translate-x-0.5 transition-transform shrink-0"
                                            style="duotone" />
                                    </div>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $link['desc'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        @if (user_is_diretoria_executive())
            <div
                class="rounded-2xl border border-violet-200/80 bg-violet-50/80 dark:border-violet-900/50 dark:bg-violet-950/30 px-5 py-4 flex gap-3 items-start">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-200/80 text-violet-800 dark:bg-violet-900/60 dark:text-violet-200">
                    <x-icon name="circle-info" class="h-5 w-5" style="duotone" />
                </div>
                <p class="text-sm text-violet-900 dark:text-violet-200 leading-relaxed">
                    <span class="font-semibold">Equipa executiva:</span> tem acesso à gestão de utilizadores, funções,
                    permissões, módulos, carrossel, Bíblia digital e restantes áreas reservadas da diretoria neste painel.
                </p>
            </div>
        @endif
    </div>
@endsection
