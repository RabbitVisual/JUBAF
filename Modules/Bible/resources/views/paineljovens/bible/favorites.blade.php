@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', 'Favoritos da Bíblia')

@php
    $abbr = \Modules\Bible\App\Models\BibleVersion::query()->value('abbreviation');
    $defaultRead = $abbr ? route('jovens.bible.read', $abbr) : route('jovens.bible.read');
@endphp

@section('jovens_content')
    <div class="space-y-8 pb-12 -mt-2">
        <header class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-50 tracking-tight">Versículos guardados</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Os trechos que marcaste no leitor aparecem aqui — tudo dentro do painel Unijovem.
                </p>
            </div>
            <a href="{{ $defaultRead }}"
               class="inline-flex shrink-0 items-center gap-2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:border-blue-400/60 transition-colors">
                <x-icon name="book-open" class="w-4 h-4 text-blue-600" />
                Voltar ao leitor
            </a>
        </header>

        @if($favorites->count() > 0)
            <div class="rounded-3xl border border-blue-200/70 dark:border-blue-900/50 bg-gradient-to-br from-blue-600 to-blue-800 text-white px-6 py-6 md:px-8 md:py-7 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-lg shadow-blue-900/10">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total na tua coleção</p>
                    <p class="text-3xl font-bold tabular-nums mt-1">{{ $favorites->count() }}</p>
                </div>
                <p class="text-sm text-blue-100/90 max-w-sm leading-relaxed">
                    Volta ao leitor quando quiseres — cada versículo continua ligado ao capítulo certo.
                </p>
            </div>
        @endif

        <div class="space-y-4">
            @forelse($favorites as $verse)
                @php
                    $chapter = $verse->chapter;
                    $book = $chapter->book;
                    $version = $book->bibleVersion;
                @endphp
                <article class="group rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/80 p-6 md:p-7 shadow-sm hover:border-blue-300/50 dark:hover:border-blue-800/50 transition-colors">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                        <a href="{{ route('jovens.bible.chapter', ['version' => $version->abbreviation, 'book' => $book->book_number, 'chapter' => $chapter->chapter_number]) }}#verse-{{ $verse->verse_number }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-gray-100 dark:bg-gray-800 px-3 py-1.5 text-xs font-bold text-gray-900 dark:text-white hover:bg-blue-50 dark:hover:bg-blue-950/50 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                            <x-icon name="bookmark" class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" />
                            {{ $verse->full_reference }}
                        </a>
                        <span class="rounded-lg border border-gray-200 dark:border-gray-700 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ $version->abbreviation }}
                        </span>
                    </div>

                    <p class="jovens-bible-serif text-lg text-gray-800 dark:text-gray-200 leading-relaxed">
                        «{{ $verse->text }}»
                    </p>

                    <div class="mt-5 flex justify-end border-t border-gray-100 dark:border-gray-800 pt-4">
                        <button type="button" onclick="removeFavorite({{ $verse->id }})"
                            class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                            Remover
                            <x-icon name="trash" class="w-3.5 h-3.5" />
                        </button>
                    </div>
                </article>
            @empty
                <div class="jovens-bible-paper rounded-3xl border border-dashed border-gray-300 dark:border-gray-700 px-8 py-16 text-center">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-400">
                        <x-icon name="heart" class="w-8 h-8" />
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Ainda não tens favoritos</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-sm mx-auto leading-relaxed">
                        Abre um capítulo no leitor e toca no coração ao lado do versículo para o guardar aqui.
                    </p>
                    <a href="{{ $defaultRead }}"
                       class="mt-8 inline-flex items-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 text-sm font-semibold shadow-md shadow-blue-600/20 transition-colors">
                        <x-icon name="book-bible" class="w-4 h-4" />
                        Ir para a Bíblia
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function removeFavorite(verseId) {
            if (!confirm('Remover este versículo dos favoritos?')) return;
            var button = event.target.closest('button');
            if (button) {
                button.disabled = true;
                button.classList.add('opacity-50');
            }
            fetch('{{ url('/social/bible/favorites') }}/' + verseId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) location.reload();
                else {
                    if (button) { button.disabled = false; button.classList.remove('opacity-50'); }
                    alert('Erro ao remover.');
                }
            })
            .catch(function() {
                if (button) { button.disabled = false; button.classList.remove('opacity-50'); }
            });
        }
    </script>
@endsection
