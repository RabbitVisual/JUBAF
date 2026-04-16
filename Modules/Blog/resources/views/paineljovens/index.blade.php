@extends('paineljovens::layouts.jovens')

@section('title', 'Blog JUBAF')


@section('jovens_content')
    <x-ui.jovens::page-shell class="space-y-6 md:space-y-8">
        <x-ui.jovens::hero
            title="Blog institucional"
            description="Notícias e artigos oficiais da JUBAF. Abre um texto para ler no painel ou no site público."
            eyebrow="Blog JUBAF">
            <x-slot:actions>
                <form method="get" action="{{ route('jovens.blog.index') }}" class="flex w-full flex-col gap-2 sm:flex-row sm:items-center" role="search">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Pesquisar…"
                        class="min-w-0 flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/25 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                    <button type="submit" class="shrink-0 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Buscar</button>
                </form>
            </x-slot:actions>
        </x-ui.jovens::hero>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-gray-50 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/40 md:p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @forelse($posts as $post)
                    @include('blog::public.partials.post-card', [
                        'post' => $post,
                        'postUrl' => route('jovens.blog.show', $post->slug),
                    ])
                @empty
                    <div class="rounded-lg border border-dashed border-gray-200 bg-white py-16 text-center dark:border-gray-700 dark:bg-gray-800 md:col-span-2">
                        <x-icon name="newspaper" class="mx-auto mb-3 h-12 w-12 text-gray-400" />
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Sem publicações por agora</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Quando a direção publicar no blog, aparece aqui.</p>
                    </div>
                @endforelse
            </div>
            @if ($posts->hasPages())
                <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>

        <p class="text-center text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('blog.index') }}" target="_blank" rel="noopener" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">Abrir blog no site público</a>
        </p>
    </x-ui.jovens::page-shell>
@endsection
