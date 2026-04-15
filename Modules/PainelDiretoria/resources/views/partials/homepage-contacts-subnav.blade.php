{{--
    Navegação interna — Mensagens de contato (Painel Diretoria).
    @var string $active lista|show — em detalhe, "Mensagens" continua destacado.
--}}
@php
    $active = $active ?? 'lista';
    $messagesActive = in_array($active, ['lista', 'show'], true);
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-blue-600 text-white shadow-md shadow-blue-600/25 ring-1 ring-blue-500/30';
@endphp
<nav class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80" aria-label="Secções de contato">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('diretoria.homepage.contacts.index') }}" class="{{ $linkBase }} {{ $messagesActive ? $linkActive : $linkIdle }}">
            <x-icon name="inbox" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Mensagens
        </a>
        <a href="{{ route('diretoria.homepage.index') }}" class="{{ $linkBase }} {{ $linkIdle }}">
            <x-icon name="home" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Editor da homepage
        </a>
    </div>
</nav>
