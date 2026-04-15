{{--
    Navegação interna Blog (Painel Diretoria).
    @var string $active posts|create|categories|tags|comments
--}}
@php
    $active = $active ?? 'posts';
    $linkBase = 'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200';
    $linkIdle = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white';
    $linkActive = 'bg-emerald-600 text-white shadow-md shadow-emerald-600/25 ring-1 ring-emerald-500/30';
    $postsListaActive = in_array($active, ['posts'], true);
@endphp
<nav
    class="rounded-2xl border border-gray-200/90 bg-white/90 p-1.5 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/80"
    aria-label="Secções do blog JUBAF">
    <div class="flex flex-wrap gap-1">
        <a href="{{ route('diretoria.blog.index') }}"
            class="{{ $linkBase }} {{ $postsListaActive ? $linkActive : $linkIdle }}">
            <x-icon name="newspaper" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Posts
        </a>
        <a href="{{ route('diretoria.blog.categories.index') }}"
            class="{{ $linkBase }} {{ $active === 'categories' ? $linkActive : $linkIdle }}">
            <x-icon name="folder-tree" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Categorias
        </a>
        <a href="{{ route('diretoria.blog.tags.index') }}"
            class="{{ $linkBase }} {{ $active === 'tags' ? $linkActive : $linkIdle }}">
            <x-icon name="tags" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Tags
        </a>
        <a href="{{ route('diretoria.blog.comments.index') }}"
            class="{{ $linkBase }} {{ $active === 'comments' ? $linkActive : $linkIdle }}">
            <x-icon name="comments" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Comentários
        </a>
        @can('create', \Modules\Blog\App\Models\BlogPost::class)
            <a href="{{ route('diretoria.blog.create') }}"
                class="{{ $linkBase }} {{ $active === 'create' ? $linkActive : $linkIdle }}">
                <x-icon name="plus" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
                Novo post
            </a>
        @endcan
        <a href="{{ route('blog.index') }}" target="_blank" rel="noopener noreferrer"
            class="{{ $linkBase }} {{ $linkIdle }}">
            <x-icon name="arrow-up-right-from-square" class="h-4 w-4 shrink-0 opacity-90" style="duotone" />
            Ver blog público
        </a>
    </div>
</nav>
