@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $authorPhoto = $post->author && $post->author->photo
        ? Storage::url($post->author->photo)
        : null;
@endphp
<section class="relative min-h-[min(52vh,520px)] w-full overflow-hidden bg-slate-900">
    @if($post->featured_image)
        <img src="{{ Storage::url($post->featured_image) }}"
             alt="{{ Str::limit(strip_tags($post->title), 120) }}"
             class="absolute inset-0 h-full w-full object-cover"
             fetchpriority="high"
             loading="eager">
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-950"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/75 to-slate-900/40"></div>

    <div class="relative z-10 mx-auto flex min-h-[min(52vh,520px)] max-w-7xl flex-col justify-end px-4 pb-12 pt-24 sm:px-6 lg:px-8 lg:pb-16 lg:pt-28">
        <nav class="mb-6 text-sm text-white/85" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-x-2 gap-y-1">
                <li><a href="{{ route('homepage') }}" class="hover:text-white">Início</a></li>
                <li class="text-white/50">/</li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-white">Blog</a></li>
                <li class="text-white/50">/</li>
                <li><a href="{{ route('blog.category', $post->category->slug) }}" class="font-medium text-blue-200 hover:text-white">{{ $post->category->name }}</a></li>
            </ol>
        </nav>

        <p class="text-xs font-semibold uppercase tracking-widest text-blue-200/95 mb-3">
            Publicado em <a href="{{ route('blog.category', $post->category->slug) }}" class="underline decoration-blue-400/80 underline-offset-2 hover:text-white">{{ $post->category->name }}</a>
        </p>

        <h1 class="text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl max-w-4xl" itemprop="headline">
            {{ $post->title }}
        </h1>

        @if($post->excerpt)
            <p class="mt-4 max-w-3xl text-lg text-slate-200/95 leading-relaxed" itemprop="description">
                {{ strip_tags($post->excerpt) }}
            </p>
        @endif

        <div class="mt-8 flex flex-wrap items-center gap-6 text-sm text-slate-200/95">
            <div class="flex items-center gap-3">
                @if($authorPhoto)
                    <img src="{{ $authorPhoto }}" alt="" width="48" height="48" class="h-12 w-12 rounded-full object-cover ring-2 ring-white/20" loading="lazy">
                @else
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-lg font-bold text-white ring-2 ring-white/20">
                        {{ Str::substr($post->author->name ?? '?', 0, 1) }}
                    </div>
                @endif
                <div>
                    <div class="text-xs uppercase tracking-wide text-white/60">Autor</div>
                    <div class="font-semibold text-white" itemprop="author">{{ $post->author->name ?? 'JUBAF' }}</div>
                </div>
            </div>
            <div class="flex flex-wrap gap-4 text-white/90">
                <span class="inline-flex items-center gap-1.5">
                    <x-icon name="calendar" class="h-4 w-4 text-blue-300" />
                    <time datetime="{{ $post->published_at->toIso8601String() }}" itemprop="datePublished">{{ $post->published_at->format('d/m/Y H:i') }}</time>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <x-icon name="clock" class="h-4 w-4 text-blue-300" />
                    {{ $post->reading_time }} min de leitura
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <x-icon name="eye" class="h-4 w-4 text-blue-300" />
                    {{ $post->views_count }} visualizações
                </span>
            </div>
        </div>
    </div>
</section>
