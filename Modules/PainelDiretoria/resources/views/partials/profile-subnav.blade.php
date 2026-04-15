{{--
    Navegação interna — Conta e perfil (Painel Diretoria).
    @var string $active perfil|pedidos
--}}
@php
    $active = $active ?? 'perfil';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25 ring-1 ring-emerald-500/30';
    $canRequests = Route::has('diretoria.profile-data-requests.index')
        && (user_is_diretoria_executive() || (function_exists('user_can_access_admin_panel') && user_can_access_admin_panel()));
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções da conta">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('diretoria.profile') }}" class="{{ $linkBase }} {{ $active === 'perfil' ? $linkActive : $linkIdle }}">
            <x-icon name="user" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Perfil
        </a>
        @if ($canRequests)
            <a href="{{ route('diretoria.profile-data-requests.index') }}" class="{{ $linkBase }} {{ $active === 'pedidos' ? $linkActive : $linkIdle }}">
                <x-icon name="envelope-open-text" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Pedidos de dados
            </a>
        @endif
    </div>
</nav>
