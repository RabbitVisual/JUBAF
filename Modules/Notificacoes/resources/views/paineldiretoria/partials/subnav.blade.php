{{--
    Navegação interna do módulo Notificações (Painel Diretoria).
    Apenas rotas diretoria.notificacoes.* — alinhado a Talentos/Secretaria.
    @var string $active lista|nova|detalhe — em "detalhe", o primeiro link (Lista) permanece ativo visualmente.
--}}
@php
    $active = $active ?? 'lista';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-indigo-600 text-white shadow-md shadow-indigo-600/25 ring-1 ring-indigo-500/30';
    $listaActive = ($active === 'lista' || $active === 'detalhe');
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções de notificações">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('diretoria.notificacoes.index') }}" class="{{ $linkBase }} {{ $listaActive ? $linkActive : $linkIdle }}">
            <x-icon name="inbox" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Lista
        </a>
        <a href="{{ route('diretoria.notificacoes.create') }}" class="{{ $linkBase }} {{ $active === 'nova' ? $linkActive : $linkIdle }}">
            <x-icon name="paper-plane-top" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Nova notificação
        </a>
    </div>
</nav>
