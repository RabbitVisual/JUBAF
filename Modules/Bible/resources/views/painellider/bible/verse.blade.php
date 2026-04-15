@extends('painellider::components.layouts.app')

@section('title', __('Versículo'))

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <a href="{{ route('lideres.bible.plans.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Bíblia</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600 dark:text-slate-300">{{ __('Versículo') }}</span>
@endsection

@section('content')
    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900">
        <p class="text-sm text-zinc-500">
            {{ $verse->chapter->book->name ?? '' }} {{ $verse->chapter->chapter_number ?? '' }}:{{ $verse->verse_number ?? '' }}
            @if ($verse->chapter->book->bibleVersion ?? null)
                · {{ $verse->chapter->book->bibleVersion->abbreviation }}
            @endif
        </p>
        <p class="mt-4 text-lg leading-relaxed text-zinc-900 dark:text-zinc-100">{{ $verse->text }}</p>
    </div>
@endsection
