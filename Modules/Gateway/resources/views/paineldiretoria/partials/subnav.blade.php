@php
    $active = $active ?? 'dashboard';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25 ring-1 ring-emerald-500/30';
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções do gateway">
    <div class="flex flex-wrap gap-1">
        @can('gateway.dashboard.view')
            <a href="{{ route('diretoria.gateway.dashboard') }}" class="{{ $linkBase }} {{ $active === 'dashboard' ? $linkActive : $linkIdle }}">
                <x-icon name="gauge-high" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Resumo
            </a>
        @endcan
        @can('gateway.payments.view')
            <a href="{{ route('diretoria.gateway.payments.index') }}" class="{{ $linkBase }} {{ $active === 'payments' ? $linkActive : $linkIdle }}">
                <x-icon name="credit-card" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Cobranças
            </a>
        @endcan
        @if (module_enabled('Financeiro') && Route::has('diretoria.financeiro.dashboard') && auth()->user()?->can('financeiro.dashboard.view'))
            <a href="{{ route('diretoria.financeiro.dashboard') }}" class="{{ $linkBase }} {{ $linkIdle }}">
                <x-module-icon module="Financeiro" class="w-4 h-4 shrink-0 opacity-90" style="duotone" />
                Tesouraria
            </a>
        @endif
    </div>
</nav>
