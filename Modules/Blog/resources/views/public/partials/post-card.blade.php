{{--
  @var \Modules\Blog\App\Models\BlogPost $post
  @var string $imageLoading lazy|eager
  @var string|null $postUrl URL do detalhe (painéis usam jovens.blog.show / lideres.blog.show)
--}}
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $imageLoading = $imageLoading ?? 'lazy';
    $postUrl = $postUrl ?? route('blog.show', $post->slug);
@endphp
<article class="group flex flex-col rounded-2xl border border-slate-200/90 dark:border-slate-700/90 bg-white dark:bg-slate-900 shadow-sm hover:shadow-xl hover:border-blue-200 dark:hover:border-blue-900/50 transition-all duration-300 overflow-hidden h-full">
    <a href="{{ $postUrl }}" class="relative block aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0">
        @if($post->featured_image)
            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ Str::limit(strip_tags($post->title), 120) }}"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                 @if($imageLoading === 'eager') fetchpriority="high" @endif
                 loading="{{ $imageLoading }}">
        @else
            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-blue-100 to-slate-100 dark:from-slate-800 dark:to-slate-900">
                <x-icon name="newspaper" class="w-14 h-14 text-blue-300/80 dark:text-slate-600" style="duotone" />
            </div>
        @endif
        <div class="absolute top-3 left-3">
            <span class="inline-block rounded-lg bg-blue-600/95 backdrop-blur-sm px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white shadow">
                {{ $post->category->name }}
            </span>
        </div>
        @if($post->is_featured)
            <div class="absolute top-3 right-3">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-400 text-amber-950 shadow-md" title="Destaque">
                    <x-icon name="star" class="w-4 h-4" solid />
                </span>
            </div>
        @endif
    </a>

    <div class="flex flex-col flex-1 p-6">
        <time class="text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5" datetime="{{ $post->published_at?->toIso8601String() }}">
            <x-icon name="calendar" class="w-3.5 h-3.5 text-blue-500" />
            {{ $post->published_at?->translatedFormat('d M Y') }}
        </time>

        <h2 class="mt-3 text-lg font-bold text-slate-900 dark:text-white leading-snug group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">
            <a href="{{ $postUrl }}">{{ $post->title }}</a>
        </h2>

        <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed flex-1">
            {{ strip_tags($post->excerpt) }}
        </p>

        <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3">
            <span class="text-xs font-medium text-slate-600 dark:text-slate-400 truncate">
                {{ Str::limit($post->author->name ?? 'JUBAF', 32) }}
            </span>
            <a href="{{ $postUrl }}" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wide text-blue-700 dark:text-blue-400 hover:underline shrink-0">
                Ler
                <x-icon name="arrow-right" class="w-3.5 h-3.5" />
            </a>
        </div>
    </div>
</article>
