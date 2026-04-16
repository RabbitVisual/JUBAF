@extends('painellider::layouts.lideres')

@section('title', __('Versículo'))

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <a href="{{ route('lideres.bible.plans.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Bíblia</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600 dark:text-slate-300">{{ __('Versículo') }}</span>
@endsection

@section('lideres_content')
@php
    $verseRef = ($verse->chapter->book->name ?? '').' '.($verse->chapter->chapter_number ?? '').':'.($verse->verse_number ?? '');
    $verseVersion = optional($verse->chapter->book->bibleVersion)->abbreviation;
@endphp
<x-ui.lideres::page-shell class="max-w-3xl">
    <x-ui.lideres::hero
        variant="surface"
        :title="$verseRef"
        :description="$verseVersion ? 'Versão '.$verseVersion : null" />

    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900">
        <p class="text-lg leading-relaxed text-zinc-900 dark:text-zinc-100">{{ $verse->text }}</p>
    </div>
</x-ui.lideres::page-shell>
@endsection
