{{--
  $tags: iterable of BlogTag (required)
  $post: optional BlogPost (edit); omit on create
--}}
@php
    $selectedTagIds = array_map(
        'intval',
        (array) old('tag_ids', isset($post) ? $post->tags->pluck('id')->all() : [])
    );
@endphp
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
            <x-icon name="tags" class="w-5 h-5 mr-2 text-emerald-600" />
            Tags
        </h3>
        <a href="{{ blog_admin_route('tags.index') }}"
           class="text-sm font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 shrink-0">
            Gerenciar tags
        </a>
    </div>

    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
        Marque as tags já cadastradas para vincular ao post (evita duplicar nomes).
    </p>

    @if($tags->isEmpty())
        <p class="text-sm text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg px-3 py-2">
            Nenhuma tag cadastrada. Crie tags em <a href="{{ blog_admin_route('tags.create') }}" class="font-semibold underline">Nova tag</a> antes de associar aqui.
        </p>
    @else
        <div class="max-h-52 overflow-y-auto rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50/80 dark:bg-slate-900/40 p-3 space-y-2">
            @foreach($tags as $tag)
                <label class="flex items-center gap-3 cursor-pointer rounded-md px-2 py-1.5 hover:bg-white dark:hover:bg-slate-800 transition-colors">
                    <input type="checkbox"
                           name="tag_ids[]"
                           value="{{ $tag->id }}"
                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-700"
                           @checked(in_array((int) $tag->id, $selectedTagIds, true))>
                    @if(! empty($tag->color))
                        <span class="h-2.5 w-2.5 rounded-full shrink-0 ring-1 ring-black/10" style="background-color: {{ $tag->color }}"></span>
                    @endif
                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ $tag->name }}</span>
                </label>
            @endforeach
        </div>
    @endif
</div>
