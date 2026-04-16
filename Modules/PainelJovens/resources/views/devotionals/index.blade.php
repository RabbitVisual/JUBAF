@extends('paineljovens::layouts.jovens')

@section('title', $pageTitle)


@section('jovens_content')
@php
    $totalCount = $rows->total();
@endphp
<x-ui.jovens::page-shell class="space-y-8 pb-8">
    <header class="relative overflow-hidden rounded-[2rem] border border-gray-200/90 dark:border-gray-800 bg-gradient-to-br from-blue-700 via-blue-800 to-gray-900 p-8 text-white shadow-xl md:p-10">
        <div class="pointer-events-none absolute inset-0 opacity-[0.12]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="relative max-w-3xl">
            <p class="text-xs font-bold uppercase tracking-widest text-blue-200/90">Edificação espiritual</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight md:text-4xl">{{ $pageTitle }}</h1>
            <p class="mt-4 text-sm leading-relaxed text-blue-100/95 md:text-base">{{ $pageLead }}</p>
            @if ($totalCount > 0)
                <p class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold ring-1 ring-white/20 backdrop-blur-sm">
                    <x-icon name="book-open" class="h-4 w-4 opacity-90" style="duotone" />
                    {{ $totalCount }} {{ $totalCount === 1 ? 'reflexão' : 'reflexões' }} publicada(s)
                </p>
            @endif
        </div>
    </header>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($rows as $row)
            <a href="{{ route('jovens.devotionals.show', $row) }}"
                class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm ring-1 ring-gray-900/5 transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-900 dark:ring-white/5 dark:hover:border-blue-600/50">
                <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-blue-100 to-gray-100 dark:from-blue-950/50 dark:to-gray-900/40">
                    @if ($row->cover_image_path)
                        <img src="{{ $row->coverImageUrl() }}" alt="" class="size-full object-cover transition duration-500 group-hover:scale-[1.03]" loading="lazy" />
                    @else
                        <div class="flex size-full items-center justify-center">
                            <x-icon name="book-open" style="duotone" class="size-14 text-blue-400/70 dark:text-blue-500/40" />
                        </div>
                    @endif
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-900/55 via-transparent to-transparent opacity-90"></div>
                    @php $cardDate = $row->devotional_date ?? $row->published_at; @endphp
                    @if ($cardDate)
                        <time datetime="{{ $cardDate->toIso8601String() }}" class="absolute bottom-3 left-3 rounded-lg bg-white/95 px-2.5 py-1 text-[11px] font-bold text-gray-800 shadow dark:bg-gray-900/95 dark:text-white">
                            {{ $cardDate->translatedFormat('d M Y') }}
                        </time>
                    @endif
                </div>
                <div class="flex flex-1 flex-col p-5">
                    @if ($row->theme)
                        <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">{{ $row->theme }}</p>
                    @endif
                    <h2 class="mt-1 text-lg font-bold leading-snug text-gray-900 transition group-hover:text-blue-700 dark:text-white dark:group-hover:text-blue-300">
                        {{ $row->title }}
                    </h2>
                    <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                        {{ \Illuminate\Support\Str::limit(strip_tags($row->body), 150) }}
                    </p>
                    <p class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-amber-800 dark:text-amber-200/90">
                        <x-icon name="book-bible" class="size-3.5 shrink-0 opacity-80" style="duotone" />
                        {{ $row->scripture_reference }}
                    </p>
                    <div class="mt-4 flex items-center gap-3 border-t border-gray-100 pt-4 dark:border-gray-800">
                        @if ($au = $row->avatarUser())
                            <x-user-avatar :user="$au" size="sm" class="!h-10 !w-10 shrink-0 ring-2 ring-blue-200 dark:ring-blue-800" />
                        @else
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-blue-800 text-sm font-bold text-white shadow-inner">
                                {{ strtoupper(\Illuminate\Support\Str::substr($row->authorDisplayName(), 0, 1)) }}
                            </span>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $row->authorDisplayName() }}</p>
                            @if ($row->authorSubtitle())
                                <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $row->authorSubtitle() }}</p>
                            @endif
                        </div>
                        <x-icon name="arrow-right" class="size-4 shrink-0 text-gray-300 transition group-hover:translate-x-0.5 group-hover:text-blue-500 dark:text-gray-600" />
                    </div>
                </div>
            </a>
        @empty
            <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50/80 px-8 py-16 text-center sm:col-span-2 xl:col-span-3 dark:border-gray-700 dark:bg-gray-900/40">
                <x-icon name="book-open" style="duotone" class="mb-4 size-14 text-gray-400" />
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">Ainda não há devocionais publicados</p>
                <p class="mt-2 max-w-md text-sm text-gray-600 dark:text-gray-400">Quando a diretoria publicar reflexões, elas aparecerão aqui.</p>
            </div>
        @endforelse
    </div>

    @if ($rows->hasPages())
        <div class="flex justify-center border-t border-gray-200 pt-8 dark:border-gray-800">
            <div class="text-gray-600 dark:text-gray-400 [&_.pagination]:gap-1">{{ $rows->links() }}</div>
        </div>
    @endif
</x-ui.jovens::page-shell>
@endsection
