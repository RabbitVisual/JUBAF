@extends('blog::layouts.blog')

@section('title', $category->name . ' - Blog JUBAF')
@section('meta_description', 'Artigos da categoria ' . $category->name . ' no blog da JUBAF — Juventude Batista Feirense.')
@section('meta_keywords', $category->name . ', JUBAF, blog, juventude batista, Feira de Santana')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <nav class="mb-8" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-x-2 text-sm text-slate-600 dark:text-slate-400">
            <li><a href="{{ route('homepage') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Início</a></li>
            <li class="text-slate-300 dark:text-slate-600">/</li>
            <li><a href="{{ route('blog.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Blog</a></li>
            <li class="text-slate-300 dark:text-slate-600">/</li>
            <li class="font-medium text-blue-600 dark:text-blue-400">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="mb-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-3">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">{{ $category->description }}</p>
        @endif
    </div>

    @if($posts->count() > 0)
        <div class="grid grid-cols-1 gap-10 xl:grid-cols-12 xl:gap-12">
            <div class="xl:col-span-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($posts as $post)
                        @include('blog::public.partials.post-card', ['post' => $post])
                    @endforeach
                </div>
                <div class="mt-12 flex justify-center">
                    {{ $posts->links() }}
                </div>
            </div>
            <aside class="xl:col-span-4 xl:sticky xl:top-28 xl:self-start">
                @include('blog::public.partials.blog-public-sidebar')
            </aside>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-8 py-16 text-center">
            <x-icon name="newspaper" class="mx-auto h-12 w-12 text-slate-400 mb-4" style="duotone" />
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">Nenhum artigo nesta categoria</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-6">Ainda não há publicações aqui.</p>
            <a href="{{ route('blog.index') }}" class="inline-flex items-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                <x-icon name="arrow-left" class="w-4 h-4 mr-2" /> Voltar ao blog
            </a>
        </div>
    @endif
</div>
@endsection
