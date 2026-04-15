{{--
    Navegação interna da tesouraria (Painel Diretoria).
    @var string $active dashboard|transactions|categories|expense_requests|reports|obligations|gateway
--}}
@php
    $active = $active ?? 'dashboard';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25 ring-1 ring-emerald-500/30';
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções da tesouraria">
    <p class="mb-2 px-2 text-[11px] text-gray-500 dark:text-gray-400">Tesouraria — escolha a secção abaixo.</p>
    <div class="flex flex-wrap gap-1">
        @can('financeiro.dashboard.view')
            <a href="{{ route('diretoria.financeiro.dashboard') }}" class="{{ $linkBase }} {{ $active === 'dashboard' ? $linkActive : $linkIdle }}">
                <x-icon name="chart-pie" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Resumo
            </a>
        @endcan
        @can('viewAny', \Modules\Financeiro\App\Models\FinTransaction::class)
            <a href="{{ route('diretoria.financeiro.transactions.index') }}" class="{{ $linkBase }} {{ $active === 'transactions' ? $linkActive : $linkIdle }}">
                <x-icon name="list" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Lançamentos
            </a>
        @endcan
        @can('financeiro.categories.view')
            <a href="{{ route('diretoria.financeiro.categories.index') }}" class="{{ $linkBase }} {{ $active === 'categories' ? $linkActive : $linkIdle }}">
                <x-icon name="tags" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Categorias
            </a>
        @endcan
        @can('viewAny', \Modules\Financeiro\App\Models\FinExpenseRequest::class)
            <a href="{{ route('diretoria.financeiro.expense-requests.index') }}" class="{{ $linkBase }} {{ $active === 'expense_requests' ? $linkActive : $linkIdle }}">
                <x-icon name="file-lines" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Reembolsos
            </a>
        @endcan
        @can('financeiro.obligations.view')
            @if (Route::has('diretoria.financeiro.obligations.index'))
                <a href="{{ route('diretoria.financeiro.obligations.index') }}" class="{{ $linkBase }} {{ $active === 'obligations' ? $linkActive : $linkIdle }}">
                    <x-icon name="coins" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                    Cotas
                </a>
            @endif
        @endcan
        @can('financeiro.reports.view')
            <a href="{{ route('diretoria.financeiro.reports.index') }}" class="{{ $linkBase }} {{ $active === 'reports' ? $linkActive : $linkIdle }}">
                <x-icon name="chart-column" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Balancete
            </a>
        @endcan
        @if (module_enabled('Gateway') && Route::has('diretoria.gateway.dashboard') && auth()->user()?->can('gateway.dashboard.view'))
            <a href="{{ route('diretoria.gateway.dashboard') }}" class="{{ $linkBase }} {{ ($active ?? '') === 'gateway' ? $linkActive : $linkIdle }}">
                <x-icon name="credit-card" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Gateway
            </a>
        @endif
    </div>
</nav>
