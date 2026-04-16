@extends('painellider::layouts.lideres')

@section('title', 'Blog JUBAF')

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="text-emerald-600 dark:text-emerald-400">Blog</span>
@endsection

@section('lideres_content')
<x-ui.lideres::page-shell class="animate-fade-in space-y-6 pb-8 md:space-y-8">
    <x-ui.lideres::hero
        variant="gradient"
        eyebrow="Conteúdo institucional"
        title="Blog institucional"
        description="Notícias e artigos oficiais da JUBAF para líderes de campo.">
        <x-slot name="actions">
            <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:items-center">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center self-start rounded-xl bg-white/15 shadow-lg sm:self-center">
                    <x-module-icon module="blog" class="h-6 w-6 text-white" alt="" />
                </span>
                <form method="get" action="{{ route('lideres.blog.index') }}" class="flex min-w-0 flex-1 gap-2 sm:max-w-md">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Pesquisar…"
                           class="min-w-0 flex-1 rounded-xl border border-white/25 bg-white/10 px-4 py-2 text-sm text-white placeholder-emerald-100/80 backdrop-blur focus:ring-2 focus:ring-white/40" />
                    <button type="submit" class="shrink-0 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-emerald-900 shadow-sm hover:bg-emerald-50">Buscar</button>
                </form>
            </div>
        </x-slot>
    </x-ui.lideres::hero>

    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950/40 md:p-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @forelse($posts as $post)
                @include('blog::public.partials.post-card', [
                    'post' => $post,
                    'postUrl' => route('lideres.blog.show', $post->slug),
                ])
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-white py-16 text-center dark:border-slate-700 dark:bg-slate-900 md:col-span-2">
                    <x-icon name="newspaper" class="mx-auto mb-3 h-12 w-12 text-slate-400" />
                    <p class="text-sm font-medium text-slate-900 dark:text-white">Sem publicações por agora</p>
                    <p class="mt-1 text-sm text-slate-500">Quando a direção publicar no blog, aparece aqui.</p>
                </div>
            @endforelse
        </div>
        @if($posts->hasPages())
            <div class="mt-6 border-t border-slate-200 pt-6 dark:border-slate-800">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    <p class="text-center text-sm text-slate-500">
        <a href="{{ route('blog.index') }}" target="_blank" rel="noopener" class="font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">Abrir blog no site público</a>
    </p>
</x-ui.lideres::page-shell>
@endsection
