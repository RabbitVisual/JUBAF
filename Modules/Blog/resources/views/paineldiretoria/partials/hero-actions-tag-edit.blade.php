<a href="{{ blog_admin_route('tags.show', $tag->id) }}"
    class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-800 transition hover:bg-blue-100 dark:border-blue-900/50 dark:bg-blue-950/40 dark:text-blue-200 dark:hover:bg-blue-950/60">
    <x-icon name="eye" class="h-4 w-4" style="duotone" />
    Ver tag
</a>
<a href="{{ blog_admin_route('tags.index') }}"
    class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-800 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
    <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
    Lista
</a>
