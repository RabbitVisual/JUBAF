{{--
    Navegação interna — Segurança e acesso (Painel Diretoria).
    @var string $active hub|users|roles|permissions
--}}
@php
    $active = $active ?? 'hub';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25 ring-1 ring-indigo-500/30';
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções de segurança e acesso">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('diretoria.seguranca.hub') }}"
            class="{{ $linkBase }} {{ $active === 'hub' ? $linkActive : $linkIdle }}"
            @if($active === 'hub') aria-current="page" @endif>
            <x-icon name="shield-halved" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            <span class="hidden min-[380px]:inline">Hub segurança</span>
            <span class="min-[380px]:hidden">Hub</span>
        </a>
        <a href="{{ route('diretoria.users.index') }}"
            class="{{ $linkBase }} {{ $active === 'users' ? $linkActive : $linkIdle }}"
            @if($active === 'users') aria-current="page" @endif>
            <x-icon name="users" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Utilizadores
        </a>
        <a href="{{ route('diretoria.roles.index') }}"
            class="{{ $linkBase }} {{ $active === 'roles' ? $linkActive : $linkIdle }}"
            @if($active === 'roles') aria-current="page" @endif>
            <x-icon name="user-shield" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Funções
        </a>
        <a href="{{ route('diretoria.permissions.index') }}"
            class="{{ $linkBase }} {{ $active === 'permissions' ? $linkActive : $linkIdle }}"
            @if($active === 'permissions') aria-current="page" @endif>
            <x-icon name="key" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Permissões
        </a>
    </div>
</nav>
