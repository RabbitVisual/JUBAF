@extends('paineljovens::layouts.jovens')

@section('title', 'Avisos JUBAF')


@section('jovens_content')
    <x-ui.jovens::page-shell class="space-y-6 md:space-y-8">
        <x-ui.jovens::hero
            title="Avisos e comunicados"
            description="Comunicados oficiais da JUBAF e da direção para ti e para a tua igreja."
            eyebrow="Avisos JUBAF">
            <x-slot:actions>
                <form method="get" action="{{ route('jovens.avisos.index') }}" class="flex w-full flex-col gap-2 sm:flex-row sm:items-center" role="search">
                    <label class="sr-only" for="avisos-q">Pesquisar</label>
                    <input id="avisos-q" type="search" name="q" value="{{ request('q') }}" placeholder="Pesquisar…"
                        class="min-w-0 flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/25 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500" />
                    <button type="submit" class="shrink-0 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Buscar</button>
                </form>
            </x-slot:actions>
        </x-ui.jovens::hero>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($avisos as $aviso)
                    <li>
                        <a href="{{ route('jovens.avisos.show', $aviso) }}" class="flex flex-col gap-3 p-5 transition-colors hover:bg-gray-50 dark:hover:bg-gray-900/50 md:p-6">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800 dark:bg-blue-900/40 dark:text-blue-200">{{ $aviso->tipo_texto }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $aviso->created_at->diffForHumans() }}</span>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $aviso->titulo }}</h2>
                            @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'card'])
                            @if ($aviso->descricao)
                                <p class="line-clamp-2 text-sm text-gray-600 dark:text-gray-400">{{ strip_tags($aviso->descricao) }}</p>
                            @endif
                            <span class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400">Abrir <x-icon name="arrow-right" class="h-4 w-4" /></span>
                        </a>
                    </li>
                @empty
                    <li class="px-6 py-16 text-center">
                        <x-icon name="bell-slash" class="mx-auto mb-3 h-12 w-12 text-gray-400" />
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Sem avisos por agora</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Quando houver novidades, aparecem aqui.</p>
                    </li>
                @endforelse
            </ul>
            @if ($avisos->hasPages())
                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/50">
                    {{ $avisos->links() }}
                </div>
            @endif
        </div>
    </x-ui.jovens::page-shell>
@endsection
