<a href="{{ blog_admin_route('tags.index') }}"
    class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-800 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
    <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
    Lista
</a>
<a href="{{ blog_admin_route('tags.edit', $tag->id) }}"
    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/25 transition hover:from-emerald-700 hover:to-emerald-800">
    <x-icon name="pen-to-square" class="h-4 w-4" style="duotone" />
    Editar
</a>
