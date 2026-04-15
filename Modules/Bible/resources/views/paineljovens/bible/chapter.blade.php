@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', $book->name . ' ' . $chapter->chapter_number . ' — ' . $version->name)

@section('content')
    <div class="pb-28 lg:pb-8 -mt-2" id="bible-chapter" style="--bible-mobile-font-size: 1.25rem">

        <nav class="sticky top-0 z-30 border-b border-stone-200/90 dark:border-stone-800 bg-stone-50/95 dark:bg-stone-950/95 backdrop-blur-md" data-tour="bible-chapter-nav" aria-label="Navegação do capítulo">
            <div class="max-w-3xl mx-auto px-4 h-14 md:h-16 flex items-center justify-between gap-2">
                <a href="{{ route('jovens.bible.book', ['version' => $version->abbreviation, 'book' => $book->book_number]) }}"
                   class="flex min-w-0 items-center gap-2 text-stone-500 hover:text-teal-800 dark:hover:text-teal-300 transition-colors rounded-xl pr-2 -ml-1">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900">
                        <x-icon name="chevron-left" class="w-4 h-4" />
                    </span>
                    <span class="hidden sm:inline text-xs font-semibold uppercase tracking-wide truncate">Capítulos</span>
                </a>

                <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                    <h1 class="truncate text-base md:text-lg font-bold text-stone-900 dark:text-stone-50">
                        {{ $book->name }}
                        <span class="text-teal-600 dark:text-teal-400">{{ $chapter->chapter_number }}</span>
                    </h1>
                    <span class="hidden sm:block h-6 w-px bg-stone-200 dark:bg-stone-700 shrink-0"></span>
                    <label class="relative shrink-0">
                        <span class="sr-only">Versão</span>
                        <select onchange="changeVersion(this.value)"
                            class="max-w-[5.5rem] sm:max-w-none appearance-none rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 py-1.5 pl-2.5 pr-7 text-xs font-bold text-stone-700 dark:text-stone-200 outline-none cursor-pointer hover:border-teal-400/60">
                            @foreach (\Modules\Bible\App\Models\BibleVersion::active()->get() as $v)
                                <option value="{{ $v->abbreviation }}" {{ $v->id === $version->id ? 'selected' : '' }}>
                                    {{ $v->abbreviation }}
                                </option>
                            @endforeach
                        </select>
                        <x-icon name="caret-down" class="pointer-events-none absolute right-1.5 top-1/2 w-3 h-3 -translate-y-1/2 text-stone-400" />
                    </label>
                </div>

                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('jovens.bible.interlinear', ['book' => $book->name, 'chapter' => $chapter->chapter_number]) }}"
                       class="hidden sm:inline-flex items-center gap-1.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white px-3 py-1.5 text-[11px] font-bold uppercase tracking-wide transition-colors">
                        <x-icon name="layer-group" class="w-3.5 h-3.5" />
                        Interlinear
                    </a>
                    <button type="button" id="bible-font-size-btn" class="sm:hidden p-2 rounded-xl text-stone-400 hover:text-teal-700 dark:hover:text-teal-400 touch-manipulation" title="Tamanho do texto" aria-label="Ajustar tamanho do texto">
                        <x-icon name="font" class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </nav>

        <main class="max-w-3xl mx-auto px-4 sm:px-6 py-8 md:py-12 jovens-bible-serif">
            @if (!empty($chapterAudioUrl))
                <div class="mb-8 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900/80 p-4 md:p-5 shadow-sm" aria-label="Áudio do capítulo">
                    <p class="text-xs font-bold text-stone-600 dark:text-stone-400 mb-3 flex items-center gap-2">
                        <x-icon name="volume-high" class="w-4 h-4 text-teal-600 dark:text-teal-400" />
                        Ouvir capítulo — {{ $version->name }}
                    </p>
                    <audio controls class="w-full max-w-md" preload="metadata" src="{{ $chapterAudioUrl }}">
                        O teu navegador não suporta áudio.
                    </audio>
                </div>
            @endif

            @if ($verses->isEmpty())
                <div class="text-center py-20">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-stone-100 dark:bg-stone-800 text-stone-400">
                        <x-icon name="triangle-exclamation" class="w-8 h-8" />
                    </div>
                    <h2 class="text-lg font-bold text-stone-900 dark:text-white">Capítulo indisponível</h2>
                    <p class="mt-2 text-sm text-stone-600 dark:text-stone-400">Este capítulo ainda não foi carregado nesta versão.</p>
                </div>
            @else
                <div class="jovens-bible-paper rounded-3xl border border-stone-200/80 dark:border-stone-800 p-5 sm:p-8 md:p-10 shadow-sm" data-tour="bible-verse">
                    <div class="space-y-5 sm:space-y-6">
                        @foreach ($verses as $verse)
                            <div class="group relative flex items-start gap-3 sm:gap-4 rounded-2xl p-2 sm:p-3 -mx-2 sm:-mx-3 transition-colors hover:bg-stone-100/80 dark:hover:bg-stone-800/40"
                                 id="verse-{{ $verse->verse_number }}">

                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-stone-200/80 dark:bg-stone-800 text-[11px] font-bold text-stone-500 dark:text-stone-400 select-none group-hover:bg-teal-100 dark:group-hover:bg-teal-950/50 group-hover:text-teal-800 dark:group-hover:text-teal-300">
                                    {{ $verse->verse_number }}
                                </span>

                                <div class="min-w-0 flex-1">
                                    <p class="bible-verse-text text-lg sm:text-xl md:text-2xl text-stone-800 dark:text-stone-200 leading-[1.75] tracking-wide">
                                        {{ $verse->text }}
                                    </p>
                                </div>

                                <div class="md:opacity-0 md:group-hover:opacity-100 flex flex-col gap-0.5 transition-opacity absolute right-1 top-1 sm:static">
                                    <button type="button" onclick="toggleFavorite({{ $verse->id }})"
                                        class="favorite-btn p-2 rounded-xl text-stone-300 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors"
                                        data-verse-id="{{ $verse->id }}"
                                        title="Guardar versículo">
                                        <x-icon name="heart" class="w-5 h-5" />
                                    </button>
                                    <button type="button"
                                        class="verse-share-btn p-2 rounded-xl text-stone-300 hover:text-teal-600 hover:bg-teal-50 dark:hover:bg-teal-950/30 transition-colors"
                                        data-verse-num="{{ (int) $verse->verse_number }}"
                                        data-verse-ref="{{ e($book->name.' '.$chapter->chapter_number.':'.$verse->verse_number.' ('.$version->abbreviation.')') }}"
                                        onclick="window.bibleShareVerse(this)"
                                        title="Copiar texto e ligação · Abrir WhatsApp"
                                        aria-label="Copiar versículo e abrir WhatsApp">
                                        <x-icon name="link" class="w-5 h-5" />
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <p class="mt-8 text-center text-xs text-stone-500 dark:text-stone-500 leading-relaxed max-w-md mx-auto">
                    Marca versículos com o coração para os reveres em <a href="{{ route('jovens.bible.favorites') }}" class="font-semibold text-teal-700 dark:text-teal-400 hover:underline">Favoritos</a>.
                </p>
            @endif
        </main>

        {{-- Acima da barra inferior do painel (mobile) --}}
        <div class="fixed bottom-20 left-0 right-0 z-30 border-t border-stone-200 dark:border-stone-800 bg-stone-50/95 dark:bg-stone-950/95 backdrop-blur-lg py-3 px-4 lg:bottom-0">
            <div class="max-w-3xl mx-auto flex items-center justify-between gap-3">
                <div class="flex-1 min-w-0">
                    @if ($previousChapter)
                        <a href="{{ route('jovens.bible.chapter', ['version' => $version->abbreviation, 'book' => $previousChapter->book->book_number, 'chapter' => $previousChapter->chapter_number]) }}"
                           class="flex items-center gap-2 text-stone-500 hover:text-teal-800 dark:hover:text-teal-300 transition-colors group max-w-full">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 group-hover:border-teal-400/50">
                                <x-icon name="chevron-left" class="w-5 h-5" />
                            </span>
                            <span class="hidden sm:block text-left min-w-0">
                                <span class="block text-[10px] font-bold uppercase tracking-widest text-stone-400">Anterior</span>
                                <span class="block text-sm font-semibold text-stone-800 dark:text-stone-200 truncate">Cap. {{ $previousChapter->chapter_number }}</span>
                            </span>
                        </a>
                    @else
                        <span class="block w-10 h-10"></span>
                    @endif
                </div>

                <div class="sm:hidden text-center px-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-teal-600 dark:text-teal-400">{{ $book->abbreviation }} {{ $chapter->chapter_number }}</span>
                </div>

                <div class="flex-1 flex justify-end min-w-0">
                    @if ($nextChapter)
                        <a href="{{ route('jovens.bible.chapter', ['version' => $version->abbreviation, 'book' => $nextChapter->book->book_number, 'chapter' => $nextChapter->chapter_number]) }}"
                           class="flex items-center gap-2 text-stone-500 hover:text-teal-800 dark:hover:text-teal-300 transition-colors group text-right max-w-full">
                            <span class="hidden sm:block text-right min-w-0">
                                <span class="block text-[10px] font-bold uppercase tracking-widest text-stone-400">Seguinte</span>
                                <span class="block text-sm font-semibold text-stone-800 dark:text-stone-200 truncate">Cap. {{ $nextChapter->chapter_number }}</span>
                            </span>
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 group-hover:border-teal-400/50">
                                <x-icon name="chevron-right" class="w-5 h-5" />
                            </span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 639px) {
            #bible-chapter .bible-verse-text {
                font-size: var(--bible-mobile-font-size) !important;
            }
        }
    </style>

    <script>
        (function() {
            var SIZES = ['1rem', '1.25rem', '1.5rem', '1.75rem', '2rem'];
            var STORAGE_KEY = 'bible_mobile_font_size_index';

            function applySize(index) {
                var el = document.getElementById('bible-chapter');
                if (!el) return;
                el.style.setProperty('--bible-mobile-font-size', SIZES[index]);
                try { localStorage.setItem(STORAGE_KEY, String(index)); } catch (e) {}
            }

            function showToast(message, type) {
                type = type || 'success';
                var toast = document.createElement('div');
                toast.className = 'fixed bottom-36 left-1/2 -translate-x-1/2 px-5 py-3 rounded-2xl shadow-2xl text-sm font-semibold transition-all duration-300 z-[60] flex items-center gap-2 ' +
                    (type === 'success' ? 'bg-stone-900 text-white dark:bg-white dark:text-stone-900' : 'bg-rose-600 text-white');
                toast.textContent = message;
                document.body.appendChild(toast);
                requestAnimationFrame(function() { toast.classList.add('opacity-100'); });
                setTimeout(function() {
                    toast.classList.add('opacity-0');
                    setTimeout(function() { toast.remove(); }, 300);
                }, 2800);
            }

            window.changeVersion = function(abbreviation) {
                @php
                    $chapterUrlTemplate = route('jovens.bible.chapter', [
                        'version' => '__V__',
                        'book' => $book->book_number,
                        'chapter' => $chapter->chapter_number,
                    ]);
                @endphp
                var tpl = @json($chapterUrlTemplate);
                window.location.href = tpl.replace('__V__', encodeURIComponent(abbreviation));
            };

            window.toggleFavorite = function(verseId) {
                var btn = document.querySelector('[data-verse-id="' + verseId + '"]');
                var isFavorite = btn && btn.classList.contains('text-rose-500');
                fetch('{{ url('/social/bible/favorites') }}/' + verseId, {
                    method: isFavorite ? 'DELETE' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!data.success) {
                        showToast('Não foi possível atualizar.', 'error');
                        return;
                    }
                    if (!isFavorite) {
                        btn.classList.remove('text-stone-300');
                        btn.classList.add('text-rose-500');
                        showToast('Guardado nos favoritos');
                    } else {
                        btn.classList.add('text-stone-300');
                        btn.classList.remove('text-rose-500');
                        showToast('Removido dos favoritos');
                    }
                })
                .catch(function() { showToast('Erro de ligação.', 'error'); });
            };

            /**
             * Copia texto + URL com âncora do versículo e abre WhatsApp (wa.me) com a mesma mensagem formatada.
             * Texto lido do DOM para evitar erros de sintaxe com aspas no HTML/JS.
             */
            window.bibleShareVerse = function(btn) {
                var num = parseInt(btn.getAttribute('data-verse-num'), 10);
                var ref = btn.getAttribute('data-verse-ref') || '';
                var row = document.getElementById('verse-' + num);
                var textEl = row ? row.querySelector('.bible-verse-text') : null;
                var text = textEl ? String(textEl.textContent || '').trim() : '';

                var shareUrl;
                try {
                    var u = new URL(window.location.href);
                    u.hash = 'verse-' + num;
                    shareUrl = u.toString();
                } catch (e) {
                    shareUrl = window.location.href.split('#')[0] + '#verse-' + num;
                }

                var body = '*' + ref + '*\n\n"' + text + '"\n\n' + shareUrl;
                var wa = 'https://wa.me/?text=' + encodeURIComponent(body);

                function openWhatsApp() {
                    if (/Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                        window.location.href = wa;
                    } else {
                        window.open(wa, '_blank', 'noopener,noreferrer');
                    }
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(body).then(function() {
                        showToast('Copiado. A abrir WhatsApp…');
                        setTimeout(openWhatsApp, 250);
                    }).catch(function() {
                        showToast('A abrir WhatsApp…');
                        openWhatsApp();
                    });
                } else {
                    showToast('A abrir WhatsApp…');
                    openWhatsApp();
                }
            };

            document.addEventListener('DOMContentLoaded', function() {
                var btn = document.getElementById('bible-font-size-btn');
                if (btn) {
                    var index = 1;
                    try {
                        index = Math.min(Math.max(0, parseInt(localStorage.getItem(STORAGE_KEY), 10) || 1), SIZES.length - 1);
                    } catch (e) {}
                    applySize(index);
                    btn.addEventListener('click', function() {
                        index = (index + 1) % SIZES.length;
                        applySize(index);
                    });
                }

                @php
                    $favoriteIds = Auth::user()->bibleFavorites()->pluck('verse_id')->toArray();
                @endphp
                var favorites = @json($favoriteIds);
                favorites.forEach(function(id) {
                    var b = document.querySelector('[data-verse-id="' + id + '"]');
                    if (b) {
                        b.classList.remove('text-stone-300');
                        b.classList.add('text-rose-500');
                    }
                });
            });
        })();
    </script>
@endsection
