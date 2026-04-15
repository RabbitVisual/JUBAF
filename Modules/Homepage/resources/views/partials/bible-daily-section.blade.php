@php
    /** @var array{payload: array, title: string, subtitle: string, show_reference: bool, show_version: bool, link_enabled: bool} $block */
    $p = $block['payload'];
@endphp
<section id="versiculo-do-dia" class="scroll-mt-24 border-y border-gray-200/90 bg-white py-14 dark:border-slate-800 dark:bg-slate-950">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">{{ $block['title'] }}</p>
            @if ($block['subtitle'] !== '')
                <p class="mt-3 text-sm text-gray-600 dark:text-slate-400 sm:text-base">{{ $block['subtitle'] }}</p>
            @endif
            <blockquote class="mt-8 font-serif text-lg italic leading-relaxed text-gray-900 dark:text-slate-100 sm:text-xl">
                “{{ $p['text'] }}”
            </blockquote>
            @if ($block['show_reference'] || $block['show_version'])
                <div class="mt-6 flex flex-wrap items-center justify-center gap-2 text-sm text-gray-600 dark:text-slate-400">
                    @if ($block['show_reference'])
                        <cite class="font-semibold not-italic text-gray-800 dark:text-slate-200">{{ $p['reference'] }}</cite>
                    @endif
                    @if ($block['show_reference'] && $block['show_version'])
                        <span aria-hidden="true" class="text-gray-300 dark:text-slate-600">·</span>
                    @endif
                    @if ($block['show_version'])
                        <span>{{ $p['version_name'] }} ({{ $p['version_abbreviation'] }})</span>
                    @endif
                </div>
            @endif
            @if ($block['link_enabled'] && !empty($p['bible_chapter_url']) && $p['bible_chapter_url'] !== '#')
                <div class="mt-8">
                    <a href="{{ $p['bible_chapter_url'] }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                        <x-icon name="book-bible" style="duotone" class="size-4" />
                        Abrir na Bíblia
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
