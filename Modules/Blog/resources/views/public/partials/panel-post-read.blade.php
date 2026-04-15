{{--
  Leitura de post nos painéis Jovens/Líder — alinhamento visual ao blog público (azul/slate).
  @var \Modules\Blog\App\Models\BlogPost $post
  @var string $publicUrl URL do artigo no site público (route blog.show)
--}}
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<article class="space-y-6 pb-8 max-w-3xl">
    <div class="flex flex-wrap items-center gap-2 text-sm">
        <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 font-semibold text-blue-900 dark:bg-blue-950/50 dark:text-blue-200">{{ $post->category->name }}</span>
        <span class="text-slate-500 dark:text-slate-400">{{ $post->published_at?->translatedFormat('d M Y, H:i') }}</span>
        @if($post->author)
            <span class="text-slate-400">·</span>
            <span class="text-slate-600 dark:text-slate-300">{{ $post->author->name }}</span>
        @endif
    </div>

    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">{{ $post->title }}</h1>

    @if($post->excerpt)
        <p class="text-slate-600 dark:text-slate-400 border-l-4 border-blue-500 pl-4 italic">{{ strip_tags($post->excerpt) }}</p>
    @endif

    <div class="flex flex-wrap gap-3">
        <a href="{{ $publicUrl }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 shadow-sm shadow-blue-600/20">
            <x-icon name="arrow-up-right-from-square" class="w-4 h-4" />
            Ver no site público
        </a>
    </div>

    @if($post->featured_image)
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full max-h-80 object-cover" loading="lazy" />
        </div>
    @endif

    <div class="prose prose-slate dark:prose-invert max-w-none prose-a:text-blue-600 dark:prose-a:text-blue-400">
        {!! $post->content !!}
    </div>
</article>
