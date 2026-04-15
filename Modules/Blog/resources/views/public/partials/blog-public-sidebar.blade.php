{{--
  Espera: latestPostsSidebar, popularTagsSidebar, sidebarCategories (controller).
  Opcional: relatedPosts — só na página do artigo.
--}}
<div class="space-y-6 lg:sticky lg:top-28">
    @if(isset($relatedPosts) && $relatedPosts->isNotEmpty())
        <div class="rounded-2xl border border-slate-200/90 dark:border-slate-700 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-4">Relacionados</h3>
            <ul class="space-y-4">
                @foreach($relatedPosts as $rp)
                    <li>
                        <a href="{{ route('blog.show', $rp->slug) }}" class="group flex gap-3 rounded-lg">
                            @if($rp->featured_image)
                                <div class="h-14 w-20 shrink-0 overflow-hidden rounded-lg bg-slate-100 dark:bg-slate-800">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($rp->featured_image) }}" alt="" class="h-full w-full object-cover transition group-hover:scale-105" loading="lazy">
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white leading-snug group-hover:text-blue-700 dark:group-hover:text-blue-400 line-clamp-2">{{ $rp->title }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $rp->published_at?->translatedFormat('d M Y') }}</p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200/90 dark:border-slate-700 bg-white dark:bg-slate-900 p-5 shadow-sm">
        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-4">Últimas publicações</h3>
        <ul class="space-y-4">
            @foreach($latestPostsSidebar as $lp)
                <li>
                    <a href="{{ route('blog.show', $lp->slug) }}" class="group flex gap-3 rounded-lg">
                        @if($lp->featured_image)
                            <div class="h-14 w-20 shrink-0 overflow-hidden rounded-lg bg-slate-100 dark:bg-slate-800">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($lp->featured_image) }}" alt="" class="h-full w-full object-cover transition group-hover:scale-105" loading="lazy">
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white leading-snug group-hover:text-blue-700 dark:group-hover:text-blue-400 line-clamp-2">{{ $lp->title }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $lp->published_at?->translatedFormat('d M Y') }} · {{ $lp->reading_time }} min</p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    @if($sidebarCategories->isNotEmpty())
        <div class="rounded-2xl border border-slate-200/90 dark:border-slate-700 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-4">Categorias</h3>
            <ul class="space-y-1">
                @foreach($sidebarCategories as $cat)
                    <li>
                        <a href="{{ route('blog.category', $cat->slug) }}" class="flex items-center justify-between rounded-lg px-2 py-1.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800">
                            <span>{{ $cat->name }}</span>
                            <span class="text-xs text-slate-400 tabular-nums">{{ $cat->published_posts_count }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($popularTagsSidebar->isNotEmpty())
        <div class="rounded-2xl border border-slate-200/90 dark:border-slate-700 bg-white dark:bg-slate-900 p-5 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-4">Tags populares</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($popularTagsSidebar as $tg)
                    <a href="{{ route('blog.tag', $tg->slug) }}" class="inline-flex items-center rounded-full border border-slate-200 dark:border-slate-600 px-2.5 py-1 text-xs font-medium text-slate-600 dark:text-slate-300 hover:border-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                        {{ $tg->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @include('blog::public.partials.jubaf-resource-links')
</div>
