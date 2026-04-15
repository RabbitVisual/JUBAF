@extends('paineljovens::components.layouts.app')

@section('title', 'Blog JUBAF')

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="text-emerald-600 dark:text-emerald-400">Blog</span>
@endsection

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-3 mb-2">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-slate-700 shadow-lg shadow-blue-600/25">
                    <x-module-icon module="blog" class="h-6 w-6 text-white" alt="" />
                </span>
                Blog institucional
            </h1>
            <p class="text-sm md:text-base text-slate-600 dark:text-slate-400">
                Notícias e artigos oficiais da JUBAF. Abre um texto para ler no painel ou no site público.
            </p>
        </div>
        <form method="get" action="{{ route('jovens.blog.index') }}" class="flex w-full sm:w-auto gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Pesquisar…"
                   class="flex-1 min-w-0 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-emerald-500" />
            <button type="submit" class="shrink-0 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Buscar</button>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/40 overflow-hidden shadow-sm p-4 md:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($posts as $post)
                @include('blog::public.partials.post-card', [
                    'post' => $post,
                    'postUrl' => route('jovens.blog.show', $post->slug),
                ])
            @empty
                <div class="md:col-span-2 py-16 text-center rounded-2xl border border-dashed border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900">
                    <x-icon name="newspaper" class="mx-auto h-12 w-12 text-slate-400 mb-3" />
                    <p class="text-sm font-medium text-slate-900 dark:text-white">Sem publicações por agora</p>
                    <p class="mt-1 text-sm text-slate-500">Quando a direção publicar no blog, aparece aqui.</p>
                </div>
            @endforelse
        </div>
        @if($posts->hasPages())
            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    <p class="text-center text-sm text-slate-500">
        <a href="{{ route('blog.index') }}" target="_blank" rel="noopener" class="font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">Abrir blog no site público</a>
    </p>
</div>
@endsection
