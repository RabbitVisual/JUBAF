@extends('layouts.app')

@section('title', $pageTitle)

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="text-violet-600 dark:text-violet-400">{{ $pageTitle }}</span>
@endsection

@section('content')
@php
    $totalCount = $rows->total();
@endphp
<div class="space-y-8 pb-8">
    <div class="relative overflow-hidden rounded-[2rem] border border-fuchsia-200/60 dark:border-fuchsia-900/40 bg-gradient-to-br from-fuchsia-600 via-violet-600 to-slate-900 p-8 md:p-10 text-white shadow-2xl shadow-fuchsia-900/20">
        <div class="absolute inset-0 opacity-25 pointer-events-none bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white/30 via-transparent to-transparent"></div>
        <div class="relative max-w-3xl">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-fuchsia-100/90">Edificação espiritual</p>
            <h1 class="mt-3 text-3xl md:text-4xl font-bold tracking-tight">{{ $pageTitle }}</h1>
            <p class="mt-4 text-sm md:text-base text-white/90 leading-relaxed">{{ $pageLead }}</p>
            @if ($totalCount > 0)
                <p class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold backdrop-blur-sm ring-1 ring-white/20">
                    <x-icon name="book-open" class="w-4 h-4 opacity-90" style="duotone" />
                    {{ $totalCount }} {{ $totalCount === 1 ? 'reflexão' : 'reflexões' }} publicada(s)
                </p>
            @endif
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($rows as $row)
            <a href="{{ route('jovens.devotionals.show', $row) }}"
                class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm ring-1 ring-slate-900/5 transition hover:-translate-y-0.5 hover:border-violet-300 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900 dark:ring-white/5 dark:hover:border-violet-600/50">
                <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-violet-100 to-fuchsia-100 dark:from-violet-950/50 dark:to-fuchsia-950/30">
                    @if ($row->cover_image_path)
                        <img src="{{ $row->coverImageUrl() }}" alt="" class="size-full object-cover transition duration-500 group-hover:scale-[1.03]" loading="lazy" />
                    @else
                        <div class="flex size-full items-center justify-center">
                            <x-icon name="book-open" style="duotone" class="size-14 text-violet-400/70 dark:text-violet-500/40" />
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/55 via-transparent to-transparent opacity-90"></div>
                    @php $cardDate = $row->devotional_date ?? $row->published_at; @endphp
                    @if ($cardDate)
                        <time datetime="{{ $cardDate->toIso8601String() }}" class="absolute bottom-3 left-3 rounded-lg bg-white/95 px-2.5 py-1 text-[11px] font-bold text-slate-800 shadow dark:bg-slate-900/95 dark:text-white">
                            {{ $cardDate->translatedFormat('d M Y') }}
                        </time>
                    @endif
                </div>
                <div class="flex flex-1 flex-col p-5">
                    @if ($row->theme)
                        <p class="text-[11px] font-bold uppercase tracking-wider text-violet-600 dark:text-violet-400">{{ $row->theme }}</p>
                    @endif
                    <h2 class="mt-1 text-lg font-bold leading-snug text-slate-900 transition group-hover:text-violet-700 dark:text-white dark:group-hover:text-violet-300">
                        {{ $row->title }}
                    </h2>
                    <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ \Illuminate\Support\Str::limit(strip_tags($row->body), 150) }}
                    </p>
                    <p class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-amber-800 dark:text-amber-200/90">
                        <x-icon name="book-bible" class="size-3.5 shrink-0 opacity-80" style="duotone" />
                        {{ $row->scripture_reference }}
                    </p>
                    <div class="mt-4 flex items-center gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        @if ($au = $row->avatarUser())
                            <x-user-avatar :user="$au" size="sm" class="!h-10 !w-10 shrink-0 ring-2 ring-violet-200 dark:ring-violet-800" />
                        @else
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-violet-600 to-fuchsia-600 text-sm font-bold text-white shadow-inner">
                                {{ strtoupper(\Illuminate\Support\Str::substr($row->authorDisplayName(), 0, 1)) }}
                            </span>
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $row->authorDisplayName() }}</p>
                            @if ($row->authorSubtitle())
                                <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $row->authorSubtitle() }}</p>
                            @endif
                        </div>
                        <x-icon name="arrow-right" class="size-4 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-violet-500 dark:text-slate-600" />
                    </div>
                </div>
            </a>
        @empty
            <div class="sm:col-span-2 xl:col-span-3 flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/80 px-8 py-16 text-center dark:border-slate-700 dark:bg-slate-900/40">
                <x-icon name="book-open" style="duotone" class="mb-4 size-14 text-slate-400" />
                <p class="text-lg font-semibold text-slate-800 dark:text-slate-200">Ainda não há devocionais publicados</p>
                <p class="mt-2 max-w-md text-sm text-slate-600 dark:text-slate-400">Quando a diretoria publicar reflexões, elas aparecerão aqui.</p>
            </div>
        @endforelse
    </div>

    @if ($rows->hasPages())
        <div class="flex justify-center border-t border-slate-200 pt-8 dark:border-slate-800">
            <div class="text-slate-600 dark:text-slate-400 [&_.pagination]:gap-1">{{ $rows->links() }}</div>
        </div>
    @endif
</div>
@endsection
