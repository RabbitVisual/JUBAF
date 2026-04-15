<a href="{{ blog_admin_route('index') }}"
    class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-800 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
    <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
    Lista de posts
</a>
@if ($post->status === 'published')
    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener noreferrer"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-800 transition hover:bg-blue-100 dark:border-blue-900/50 dark:bg-blue-950/40 dark:text-blue-200 dark:hover:bg-blue-950/60">
        <x-icon name="eye" class="h-4 w-4" style="duotone" />
        Ver no site
    </a>
@endif
<a href="{{ blog_admin_route('edit', $post->id) }}"
    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/25 transition hover:from-emerald-700 hover:to-emerald-800">
    <x-icon name="pen-to-square" class="h-4 w-4" style="duotone" />
    Editar
</a>
