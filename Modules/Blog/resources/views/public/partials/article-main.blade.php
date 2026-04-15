@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@if($post->tags->count() > 0)
    <div class="flex flex-wrap gap-3">
        @foreach($post->tags as $tag)
            <a href="{{ route('blog.tag', $tag->slug) }}"
               class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-800 transition hover:bg-blue-100 dark:border-blue-700 dark:bg-blue-950/50 dark:text-blue-200 dark:hover:bg-blue-900/60">
                <x-icon name="tag" class="mr-1.5 h-3.5 w-3.5" />
                {{ $tag->name }}
            </a>
        @endforeach
    </div>
@endif

@if($post->gallery_images && count($post->gallery_images) > 0)
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 lg:p-8">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="flex items-center text-xl font-bold text-slate-900 dark:text-white">
                <x-icon name="images" class="mr-2 h-6 w-6 text-blue-600 dark:text-blue-400" />
                Galeria
            </h2>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                {{ count($post->gallery_images) }} {{ count($post->gallery_images) === 1 ? 'imagem' : 'imagens' }}
            </span>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($post->gallery_images as $index => $image)
                <div class="group relative cursor-pointer overflow-hidden rounded-xl shadow-md transition hover:shadow-xl"
                     onclick="openGalleryModal({{ $index }})">
                    <div class="aspect-square overflow-hidden">
                        <img src="{{ Storage::url($image) }}"
                             alt="Imagem {{ $index + 1 }} — {{ Str::limit(strip_tags($post->title), 80) }}"
                             class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                             loading="lazy">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 transition group-hover:opacity-100"></div>
                </div>
            @endforeach
        </div>
    </div>

    <div id="gallery-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/95 p-4">
        <div class="relative w-full max-w-6xl max-h-screen">
            <button id="close-gallery-modal" type="button"
                    class="absolute -top-12 right-0 z-60 rounded-full bg-black/50 p-2 text-white hover:bg-black/70">
                <x-icon name="xmark" class="h-6 w-6" />
            </button>
            <div class="absolute -top-12 left-0 rounded-full bg-black/50 px-3 py-1 text-sm text-white">
                <span id="image-counter">1</span> / {{ count($post->gallery_images) }}
            </div>
            <div id="gallery-modal-content" class="relative overflow-hidden rounded-2xl bg-black shadow-2xl">
                @foreach($post->gallery_images as $index => $image)
                    <div class="gallery-modal-slide {{ $index === 0 ? 'block' : 'hidden' }}" data-slide="{{ $index }}">
                        <img src="{{ Storage::url($image) }}" alt="" class="max-h-screen w-full object-contain">
                    </div>
                @endforeach
            </div>
            @if(count($post->gallery_images) > 1)
                <button id="modal-prev" type="button" class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-3 text-white hover:bg-black/70">
                    <x-icon name="chevron-left" class="h-6 w-6" />
                </button>
                <button id="modal-next" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-black/50 p-3 text-white hover:bg-black/70">
                    <x-icon name="chevron-right" class="h-6 w-6" />
                </button>
                <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-2 rounded-full bg-black/50 px-4 py-2">
                    @foreach($post->gallery_images as $index => $image)
                        <button type="button" class="gallery-thumbnail h-3 w-3 rounded-full bg-white {{ $index === 0 ? 'bg-opacity-100' : 'bg-opacity-50' }}"
                                data-slide="{{ $index }}" onclick="goToSlide({{ $index }})"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif

@if($post->attachments && count($post->attachments) > 0)
    <div class="rounded-2xl border border-blue-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 lg:p-8">
        <h2 class="mb-6 flex items-center text-xl font-bold text-slate-900 dark:text-white">
            <x-icon name="paperclip" class="mr-2 h-6 w-6 text-blue-600 dark:text-blue-400" />
            Anexos e documentos
        </h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach($post->attachments as $attachment)
                <a href="{{ Storage::url($attachment['path']) }}" target="_blank" rel="noopener"
                   class="group flex items-center rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-blue-300 dark:border-slate-600 dark:bg-slate-800 dark:hover:border-blue-600">
                    <div class="rounded-lg bg-red-100 p-3 dark:bg-red-900/40">
                        <x-icon name="file-pdf" class="h-6 w-6 text-red-600 dark:text-red-400" />
                    </div>
                    <div class="ml-4 min-w-0 flex-1">
                        <div class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $attachment['name'] }}</div>
                        <div class="mt-1 text-xs font-bold uppercase tracking-widest text-slate-500">PDF</div>
                    </div>
                    <x-icon name="download" class="ml-auto h-4 w-4 shrink-0 text-slate-400 group-hover:text-blue-600" />
                </a>
            @endforeach
        </div>
    </div>
@endif

<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 lg:p-10">
    <div class="blog-post-body prose prose-lg prose-slate max-w-none dark:prose-invert
        prose-headings:scroll-mt-28 prose-headings:font-bold prose-headings:tracking-tight
        prose-headings:text-slate-900 dark:prose-headings:text-white
        prose-h2:mt-10 prose-h2:border-b prose-h2:border-slate-200 dark:prose-h2:border-slate-600 prose-h2:pb-2
        prose-p:text-slate-700 dark:prose-p:text-slate-300 prose-p:leading-relaxed
        prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-a:no-underline hover:prose-a:underline
        prose-img:rounded-xl prose-img:shadow-md
        [&_iframe]:max-w-full [&_iframe]:rounded-xl [&_.ql-align-center]:text-center [&_.ql-align-right]:text-right"
        itemprop="articleBody">
        {!! htmlspecialchars_decode($post->content) !!}
    </div>
</div>

@if($post->module_data && $post->auto_generated_from)
    <div class="rounded-2xl border border-blue-100 bg-white p-6 dark:border-blue-900/30 dark:bg-slate-900 lg:p-8">
        <h2 class="mb-6 flex items-center text-xl font-bold text-slate-900 dark:text-white">
            <x-icon name="chart-mixed" class="mr-2 h-6 w-6 text-blue-600 dark:text-blue-400" />
            Dados relacionados — {{ ucfirst($post->auto_generated_from) }}
        </h2>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            @foreach($post->module_data as $key => $value)
                <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4 text-center dark:border-blue-800/50 dark:bg-blue-900/10">
                    <div class="mb-1 text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $value }}</div>
                    <div class="text-xs font-bold uppercase tracking-widest text-blue-800/70 dark:text-blue-300/70">{{ str_replace('_', ' ', $key) }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 dark:border-slate-700 dark:bg-slate-800/80">
    <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Partilhar</h2>
    <div class="flex flex-wrap gap-3">
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener"
           class="inline-flex items-center rounded-lg bg-[#1877F2] px-4 py-2 text-sm font-medium text-white hover:bg-[#166fe5]">
            <x-icon name="facebook" brand class="mr-2 h-4 w-4" /> Facebook
        </a>
        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener"
           class="inline-flex items-center rounded-lg bg-[#1DA1F2] px-4 py-2 text-sm font-medium text-white hover:bg-[#1a91da]">
            <x-icon name="twitter" brand class="mr-2 h-4 w-4" /> Twitter
        </a>
        <a href="https://wa.me/?text={{ urlencode($post->title.' - '.url()->current()) }}" target="_blank" rel="noopener"
           class="inline-flex items-center rounded-lg bg-[#25D366] px-4 py-2 text-sm font-medium text-white hover:bg-[#21bd5c]">
            <x-icon name="whatsapp" brand class="mr-2 h-4 w-4" /> WhatsApp
        </a>
        <button type="button" onclick="copyToClipboard(event, @js($canonicalPostUrl ?? url()->current()))"
                class="inline-flex items-center rounded-lg bg-slate-600 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
            <x-icon name="copy" class="mr-2 h-4 w-4" /> Copiar link
        </button>
        <button type="button" id="blog-native-share" hidden
                class="inline-flex items-center rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
            <x-icon name="share-nodes" class="mr-2 h-4 w-4" /> Partilhar
        </button>
    </div>
</div>

@if($post->allow_comments)
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 lg:p-8">
        <h2 class="mb-6 flex items-center text-xl font-bold text-slate-900 dark:text-white">
            <x-icon name="comments" class="mr-2 h-6 w-6 text-blue-600 dark:text-blue-400" />
            Comentários ({{ $post->approvedComments->count() }})
        </h2>
        @if($post->approvedComments->count() > 0)
            <div class="mb-8 space-y-4">
                @foreach($post->approvedComments as $comment)
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-500 text-sm font-bold text-white">
                                {{ substr($comment->user->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="mb-1 flex flex-wrap items-center gap-2">
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $comment->user->name ?? 'Anónimo' }}</span>
                                    <span class="text-sm text-slate-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="text-slate-700 dark:text-slate-300">{{ $comment->content }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        @auth
            <div class="border-t border-slate-200 pt-6 dark:border-slate-700">
                <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Deixe o seu comentário</h3>
                <form action="{{ route('blog.comments.store', $post->id) }}" method="POST">
                    @csrf
                    <textarea name="content" rows="4" required
                              class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                              placeholder="O seu comentário…"></textarea>
                    <button type="submit" class="mt-4 rounded-lg bg-blue-600 px-6 py-2 font-medium text-white hover:bg-blue-700">
                        Enviar
                    </button>
                </form>
            </div>
        @else
            <div class="py-8 text-center">
                <p class="mb-4 text-slate-600 dark:text-slate-400">Inicie sessão para comentar.</p>
                <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">Iniciar sessão</a>
            </div>
        @endauth
    </div>
@endif

@if($previousPost || $nextPost)
    <nav class="grid gap-4 sm:grid-cols-2" aria-label="Navegação entre artigos">
        @if($previousPost)
            <a href="{{ route('blog.show', $previousPost->slug) }}"
               class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-900">
                <x-icon name="chevron-left" class="h-6 w-6 shrink-0 text-slate-400" />
                <div class="min-w-0">
                    <div class="text-xs text-slate-500">Anterior</div>
                    <div class="line-clamp-2 font-medium text-slate-900 dark:text-white">{{ $previousPost->title }}</div>
                </div>
            </a>
        @else
            <div></div>
        @endif
        @if($nextPost)
            <a href="{{ route('blog.show', $nextPost->slug) }}"
               class="flex items-center justify-end gap-3 rounded-xl border border-slate-200 bg-white p-4 text-right shadow-sm transition hover:shadow-md dark:border-slate-700 dark:bg-slate-900 sm:text-right">
                <div class="min-w-0">
                    <div class="text-xs text-slate-500">Seguinte</div>
                    <div class="line-clamp-2 font-medium text-slate-900 dark:text-white">{{ $nextPost->title }}</div>
                </div>
                <x-icon name="chevron-right" class="h-6 w-6 shrink-0 text-slate-400" />
            </a>
        @endif
    </nav>
@endif
