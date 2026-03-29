@extends('homepage::components.layouts.master')

@section('content')
    <article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <header class="mb-8">
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ optional($communication->published_at)->translatedFormat('d \\d\\e F \\d\\e Y') }}</p>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $communication->title }}</h1>
            @if($communication->summary)
                <p class="text-lg text-gray-600 dark:text-gray-400 mt-3">{{ $communication->summary }}</p>
            @endif
        </header>
        <div class="prose prose-gray dark:prose-invert max-w-none">
            {!! nl2br(e($communication->body)) !!}
        </div>
        <p class="mt-10">
            <a href="{{ route('public.transparency.index') }}" class="text-blue-600 dark:text-blue-400 font-medium hover:underline">← Voltar à transparência</a>
        </p>
    </article>
@endsection
