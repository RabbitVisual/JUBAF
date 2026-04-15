@extends('homepage::layouts.homepage')

@php
    $pageH1 = trim((string) ($s['homepage_devotionals_page_title'] ?? '')) ?: 'Devocionais';
    $pageLead = trim((string) ($s['homepage_devotionals_page_lead'] ?? ''));
    $totalCount = $rows->total();
@endphp

@section('title', $metaTitle)

@section('content')
@include('homepage::layouts.navbar-homepage')

{{-- Herói minimalista --}}
<section class="border-b border-gray-200/90 bg-white dark:border-slate-800 dark:bg-slate-950" aria-labelledby="devotionals-hero-title">
    <div class="container mx-auto px-4 py-12 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
        <div class="mx-auto max-w-4xl text-center lg:mx-0 lg:text-left">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">Edificação espiritual</p>
            <h1 id="devotionals-hero-title" class="mt-3 font-poppins text-3xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white sm:text-4xl lg:text-5xl">
                {{ $pageH1 }}
            </h1>
            @if ($pageLead !== '')
                <p class="mx-auto mt-6 max-w-2xl text-base leading-relaxed text-gray-600 dark:text-slate-300 sm:text-lg lg:mx-0">
                    {{ $pageLead }}
                </p>
            @endif

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3 lg:justify-start">
                @if ($totalCount > 0)
                    <span class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-200">
                        <x-icon name="books" style="duotone" class="size-4 text-amber-600 dark:text-amber-400" />
                        {{ $totalCount }} {{ $totalCount === 1 ? 'reflexão publicada' : 'reflexões publicadas' }}
                    </span>
                @endif
                <a href="{{ route('homepage') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <x-icon name="house" style="duotone" class="size-4" />
                    Voltar ao início
                </a>
                @if (module_enabled('Bible') && Route::has('bible.public.index'))
                    <a href="{{ route('bible.public.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                        <x-icon name="book-bible" style="duotone" class="size-4 text-amber-600 dark:text-amber-400" />
                        Bíblia online
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Lista em painel elevado --}}
<div class="relative min-h-[40vh] bg-gray-50 pb-20 pt-0 dark:bg-slate-950 sm:pb-24">
    <div class="container relative z-10 mx-auto px-4 sm:px-6 lg:px-8">
        <div class="-mt-10 rounded-3xl border border-gray-200/90 bg-white/95 p-6 shadow-2xl shadow-blue-900/5 backdrop-blur-md dark:border-slate-700/90 dark:bg-slate-900/95 dark:shadow-black/40 sm:p-8 lg:-mt-14 lg:p-10">
            <div class="flex flex-col gap-4 border-b border-gray-100 pb-8 dark:border-slate-800 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">Biblioteca</p>
                    <h2 class="mt-2 font-poppins text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                        Últimas reflexões
                    </h2>
                    <p class="mt-2 max-w-xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                        Leia, partilhe e aprofunde a Palavra com o apoio da referência bíblica em cada devocional.
                    </p>
                </div>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                @forelse ($rows as $row)
                    <a href="{{ route('devocionais.show', $row) }}"
                        class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-md ring-1 ring-black/5 transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl dark:border-slate-700/90 dark:bg-slate-950 dark:ring-white/5 dark:hover:border-blue-500/40">
                        @if ($rows->currentPage() === 1 && $loop->first && $totalCount > 0)
                            <span class="absolute end-4 top-4 z-20 inline-flex items-center rounded-full bg-amber-500 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-white shadow-md">
                                Destaque
                            </span>
                        @endif
                        <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-blue-100/80 to-slate-200/60 dark:from-blue-950/40 dark:to-slate-900/80">
                            @if ($row->cover_image_path)
                                <img src="{{ $row->coverImageUrl() }}" alt="" class="size-full object-cover transition duration-500 group-hover:scale-[1.04]" loading="lazy" />
                            @else
                                <div class="flex size-full flex-col items-center justify-center gap-2 p-6 text-center">
                                    <x-icon name="book-open" style="duotone" class="size-14 text-blue-400/60 dark:text-blue-500/40" />
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent opacity-80 dark:from-slate-950/70"></div>
                            @php
                                $cardDate = $row->devotional_date ?? $row->published_at;
                            @endphp
                            @if ($cardDate)
                                <time datetime="{{ $cardDate instanceof \Carbon\CarbonInterface ? $cardDate->toIso8601String() : '' }}" class="absolute bottom-3 start-3 rounded-lg bg-white/90 px-2.5 py-1 text-[11px] font-bold text-slate-800 shadow-sm backdrop-blur-sm dark:bg-slate-900/90 dark:text-white">
                                    {{ $cardDate->translatedFormat('d M Y') }}
                                </time>
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col p-5 sm:p-6">
                            @if ($row->theme)
                                <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">{{ $row->theme }}</p>
                            @endif
                            <h3 class="mt-1 font-poppins text-lg font-bold leading-snug text-gray-900 transition group-hover:text-blue-800 dark:text-white dark:group-hover:text-blue-300">
                                {{ $row->title }}
                            </h3>
                            <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                                {{ \Illuminate\Support\Str::limit(strip_tags($row->body), 160) }}
                            </p>
                            <p class="mt-4 inline-flex items-center gap-2 text-xs font-bold text-amber-800 dark:text-amber-200/90">
                                <x-icon name="book-bible" class="size-3.5 shrink-0 opacity-80" />
                                {{ $row->scripture_reference }}
                            </p>
                            <div class="mt-5 flex items-center gap-3 border-t border-gray-100 pt-4 dark:border-slate-800">
                                <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-sm font-bold text-white shadow-inner">
                                    {{ strtoupper(\Illuminate\Support\Str::substr($row->authorDisplayName(), 0, 1)) }}
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $row->authorDisplayName() }}</p>
                                    @if ($row->authorSubtitle())
                                        <p class="truncate text-xs text-gray-500 dark:text-slate-400">{{ $row->authorSubtitle() }}</p>
                                    @endif
                                </div>
                                <x-icon name="arrow-right" class="ms-auto size-4 shrink-0 text-gray-300 transition group-hover:translate-x-0.5 group-hover:text-blue-500 dark:text-slate-600" />
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-slate-50/80 px-8 py-16 text-center dark:border-slate-700 dark:bg-slate-900/50 sm:col-span-2 lg:col-span-3">
                        <x-icon name="book-open" style="duotone" class="mb-4 size-14 text-gray-400 dark:text-slate-500" />
                        <p class="font-poppins text-lg font-semibold text-gray-800 dark:text-gray-200">Ainda não há devocionais publicados</p>
                        <p class="mt-2 max-w-md text-sm text-gray-600 dark:text-slate-400">
                            Novas reflexões aparecerão aqui assim que forem publicadas.
                        </p>
                        <a href="{{ route('homepage') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg transition hover:bg-blue-700">
                            <x-icon name="house" style="duotone" class="size-4" />
                            Voltar ao início
                        </a>
                    </div>
                @endforelse
            </div>

            @if ($rows->hasPages())
                <div class="mt-12 flex justify-center border-t border-gray-100 pt-10 dark:border-slate-800">
                    <div class="text-gray-600 dark:text-slate-400 [&_.pagination]:gap-1">{{ $rows->links() }}</div>
                </div>
            @endif
        </div>
    </div>
</div>

@include('homepage::layouts.footer-homepage')
@endsection
