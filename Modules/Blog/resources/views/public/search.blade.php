@extends('blog::layouts.blog')

@section('title', 'Buscar: "' . $query . '" - Blog JUBAF')
@section('meta_description', 'Resultados da busca por "' . $query . '" no blog da JUBAF — Juventude Batista Feirense.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <nav class="mb-8" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-x-2 text-sm text-slate-600 dark:text-slate-400">
            <li><a href="{{ route('homepage') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Início</a></li>
            <li class="text-slate-300 dark:text-slate-600">/</li>
            <li><a href="{{ route('blog.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Blog</a></li>
            <li class="text-slate-300 dark:text-slate-600">/</li>
            <li class="font-medium text-blue-600 dark:text-blue-400">Busca</li>
        </ol>
    </nav>

    <div class="mb-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-3">Resultados da busca</h1>
        <p class="text-lg text-slate-600 dark:text-slate-400 max-w-3xl mx-auto mb-6">
            Pesquisa: <span class="font-semibold text-blue-600 dark:text-blue-400">"{{ e($query) }}"</span>
        </p>
        <form action="{{ route('blog.search') }}" method="GET" class="max-w-md mx-auto">
            <div class="relative">
                <input type="text" name="q" value="{{ $query }}"
                    class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-12 pr-4 text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                    placeholder="Buscar no blog…">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex w-12 items-center justify-center text-slate-400">
                    <x-icon name="magnifying-glass" class="h-5 w-5" />
                </div>
            </div>
        </form>
    </div>

    @if($posts->count() > 0)
        <p class="mb-6 text-sm text-slate-600 dark:text-slate-400">
            {{ $posts->total() }} resultado(s) encontrado(s)
        </p>
        <div class="grid grid-cols-1 gap-10 xl:grid-cols-12 xl:gap-12">
            <div class="xl:col-span-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($posts as $post)
                        @include('blog::public.partials.post-card', ['post' => $post])
                    @endforeach
                </div>
                <div class="mt-12 flex justify-center">
                    {{ $posts->appends(['q' => $query])->links() }}
                </div>
            </div>
            <aside class="xl:col-span-4 xl:sticky xl:top-28 xl:self-start">
                @include('blog::public.partials.blog-public-sidebar')
            </aside>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-8 py-16 text-center">
            <x-icon name="magnifying-glass" class="mx-auto h-12 w-12 text-slate-400 mb-4" />
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">Nenhum resultado</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-8">Tente outros termos ou volte ao índice do blog.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    <x-icon name="arrow-left" class="w-4 h-4 mr-2" /> Blog
                </a>
                <a href="{{ route('homepage') }}" class="inline-flex items-center rounded-xl bg-slate-600 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-700">
                    <x-icon name="house" class="w-4 h-4 mr-2" /> Início
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
