@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', $book->name . ' — ' . $version->name)

@section('jovens_content')
    <div class="space-y-8 pb-12 -mt-2">
        <header class="sticky top-0 z-20 -mx-4 px-4 md:mx-0 md:px-0 md:static bg-gray-50/95 dark:bg-gray-950/95 md:bg-transparent backdrop-blur md:backdrop-none border-b border-gray-200/80 dark:border-gray-800 md:border-0 pb-4 md:pb-0">
            <div class="flex items-start gap-4">
                <a href="{{ route('jovens.bible.read', $version->abbreviation) }}"
                   class="mt-1 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-500 hover:text-blue-700 hover:border-blue-400/50 transition-colors"
                   aria-label="Voltar aos livros">
                    <x-icon name="arrow-left" class="w-4 h-4" />
                </a>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-blue-700 dark:text-blue-400">
                        {{ $book->testament == 'old' ? 'Antigo Testamento' : 'Novo Testamento' }}
                    </p>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-50 tracking-tight">{{ $book->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ $chapters->count() }} capítulos · {{ $version->abbreviation }}
                    </p>
                </div>
            </div>
        </header>

        <div class="relative overflow-hidden rounded-3xl border border-blue-200/60 dark:border-blue-900/50 bg-gradient-to-br from-blue-600 to-blue-800 text-white shadow-lg shadow-blue-900/10">
            <div class="absolute inset-0 opacity-30 pointer-events-none" style="background-image: radial-gradient(circle at 20% 20%, white 0, transparent 45%), radial-gradient(circle at 80% 80%, rgba(255,255,255,0.2) 0, transparent 40%);"></div>
            <div class="relative px-6 py-8 md:px-10 md:py-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <p class="text-blue-100/90 text-sm font-medium max-w-md leading-relaxed">
                        Toca num capítulo para abrir o texto na tradução <strong class="text-white">{{ $version->abbreviation }}</strong>. O leitor usa tipografia ampla, ideal para telemóvel e desktop.
                    </p>
                </div>
                <div class="flex gap-3 shrink-0">
                    <a href="{{ route('jovens.bible.search') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/15 hover:bg-white/25 px-4 py-2.5 text-xs font-semibold border border-white/20 transition-colors">
                        <x-icon name="magnifying-glass" class="w-4 h-4" />
                        Buscar palavra
                    </a>
                    <a href="{{ route('jovens.bible.favorites') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white text-blue-900 px-4 py-2.5 text-xs font-semibold hover:bg-blue-50 transition-colors">
                        <x-icon name="star" class="w-4 h-4 text-amber-500" />
                        Favoritos
                    </a>
                </div>
            </div>
        </div>

        <section class="jovens-bible-paper rounded-3xl border border-gray-200/90 dark:border-gray-800 p-6 md:p-8 shadow-sm">
            @if($chapters->isEmpty())
                <div class="text-center py-16">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-400">
                        <x-icon name="triangle-exclamation" class="w-8 h-8" />
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Sem capítulos</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                        Esta tradução ainda não tem este livro carregado. Experimenta outra versão no leitor ou contacta a equipa JUBAF.
                    </p>
                    <a href="{{ route('jovens.bible.read', $version->abbreviation) }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-blue-700 dark:text-blue-400 hover:underline">
                        <x-icon name="arrow-left" class="w-4 h-4" />
                        Voltar à lista de livros
                    </a>
                </div>
            @else
                <div class="grid grid-cols-5 sm:grid-cols-7 md:grid-cols-9 lg:grid-cols-10 xl:grid-cols-12 gap-2">
                    @foreach($chapters as $chapter)
                        <a href="{{ route('jovens.bible.chapter', ['version' => $version->abbreviation, 'book' => $book->book_number, 'chapter' => $chapter->chapter_number]) }}"
                           class="group flex aspect-square flex-col items-center justify-center rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm font-bold hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/50 hover:text-blue-900 dark:hover:text-blue-100 transition-colors">
                            <span class="group-hover:scale-105 transition-transform">{{ $chapter->chapter_number }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection
