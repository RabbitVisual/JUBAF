{{-- Corpo da Bíblia interlinear (público ou painel). Requer window.__interlinearConfig e Alpine.data('interlinearApp') em resources/js/bible-interlinear.js. --}}
<div class="bible-sacred-root interlinear-root w-full min-w-0 min-h-[calc(100vh-12rem)] transition-colors duration-500 pb-28"
     :class="isStudyMode ? 'fixed inset-0 z-[60] overflow-y-auto min-h-screen w-full bg-[color:var(--sacred-parchment)]' : ''"
     x-data="interlinearApp"
     x-effect="document.body.classList.toggle('interlinear-focus-mode', isStudyMode)"
     :data-bible-palette="biblePalette"
     @scroll.window="updateProgress()"
     @scroll="updateProgress()">

    <div class="fixed top-0 left-0 right-0 h-0.5 z-[70] bg-gradient-to-r from-[color:var(--sacred-accent)] via-[color:var(--sacred-gold)] to-[color:var(--sacred-accent)] transition-all duration-300 pointer-events-none"
         :style="'width: ' + scrollProgress + '%'"></div>

    <div x-show="interlinearError" x-cloak x-transition
         class="max-w-[88rem] mx-auto px-3 sm:px-6 lg:px-8 pt-3">
        <div class="bible-sacred-alert-error" role="alert">
            <p class="font-bold text-[color:var(--sacred-ink)]">{{ __('Interlinear indisponível') }}</p>
            <p class="mt-1 opacity-90" x-text="interlinearError"></p>
        </div>
    </div>

    <header class="sticky top-0 z-[65] bible-sacred-header border-b-2 border-[color:var(--parchment-edge)] shadow-md">
        <div class="max-w-[88rem] mx-auto px-3 sm:px-6 lg:px-8 py-3 sm:py-4 space-y-3">
            <div class="flex items-start sm:items-center gap-3 min-w-0">
                <a :href="panelMode ? backUrl : publicBibleIndexUrl"
                   class="shrink-0 flex items-center justify-center w-11 h-11 rounded-xl border border-[color:var(--parchment-edge)] bg-[color:var(--sacred-parchment-deep)]/40 text-[color:var(--accent)] hover:bg-[color:var(--sacred-parchment-deep)]/70 transition-colors"
                   :aria-label="panelMode ? backLabel : @js(__('Voltar à Bíblia online'))">
                    <x-icon name="arrow-left" class="w-5 h-5" />
                </a>
                <div class="min-w-0 flex-1">
                    <h1 class="font-scripture text-lg sm:text-2xl font-bold text-[color:var(--ink)] tracking-tight leading-snug">
                        <span class="sm:hidden">{{ __('Interlinear') }}</span>
                        <span class="hidden sm:inline">{{ __('Escrituras') }} <span class="text-[color:var(--gold)]">{{ __('Interlineares') }}</span></span>
                    </h1>
                    <p class="text-[10px] sm:text-xs uppercase tracking-[0.18em] text-[color:var(--ink-muted)] mt-0.5">{{ __('Texto original · Léxico Strong · Comparar versões') }}</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:flex-wrap items-stretch gap-2 sm:gap-2.5">
                <div class="flex rounded-xl border border-[color:var(--parchment-edge)] p-0.5 bg-white/50 dark:bg-black/25 shrink-0">
                    <button type="button" @click="selectedTestament = 'old'; loadBooks().then(() => loadChapters())"
                            :class="selectedTestament === 'old' ? 'bg-[color:var(--accent)] text-white shadow-sm' : 'text-[color:var(--ink-muted)] hover:bg-white/70 dark:hover:bg-stone-800'"
                            class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-black uppercase tracking-wide transition-all min-h-11">{{ __('Antigo T.') }}</button>
                    <button type="button" @click="selectedTestament = 'new'; loadBooks().then(() => loadChapters())"
                            :class="selectedTestament === 'new' ? 'bg-[color:var(--accent)] text-white shadow-sm' : 'text-[color:var(--ink-muted)] hover:bg-white/70 dark:hover:bg-stone-800'"
                            class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-black uppercase tracking-wide transition-all min-h-11">{{ __('Novo T.') }}</button>
                </div>

                <select x-model="selectedVersionAbbrev" @change="loadData()"
                        class="w-full sm:flex-1 sm:min-w-[min(100%,12rem)] md:min-w-[14rem] lg:min-w-[16rem] text-sm font-semibold rounded-xl border border-[color:var(--parchment-edge)] bg-white/85 dark:bg-stone-900/70 text-[color:var(--ink)] px-3 py-2.5 min-h-11 shadow-sm focus:ring-2 focus:ring-[color:var(--accent)]/30 focus:outline-none">
                    <template x-for="v in bibleVersions" :key="v.abbreviation">
                        <option :value="v.abbreviation" x-text="v.abbreviation + ' — ' + v.name"></option>
                    </template>
                </select>

                <select x-model="selectedBook" @change="loadChapters()"
                        class="w-full sm:flex-1 sm:min-w-[min(100%,10rem)] md:min-w-[12rem] text-sm font-semibold rounded-xl border border-[color:var(--parchment-edge)] bg-white/85 dark:bg-stone-900/70 text-[color:var(--ink)] px-3 py-2.5 min-h-11 shadow-sm focus:ring-2 focus:ring-[color:var(--accent)]/30 focus:outline-none">
                    <template x-for="book in books" :key="book.name">
                        <option :value="book.name" x-text="book.name"></option>
                    </template>
                </select>

                <div class="flex items-center justify-center gap-1 rounded-xl border border-[color:var(--parchment-edge)] px-1 bg-white/50 dark:bg-black/25 shrink-0 min-h-11">
                    <button type="button" @click="prevChapter()" class="p-2.5 rounded-lg hover:bg-[color:var(--sacred-parchment-deep)] text-[color:var(--accent)] min-w-11 min-h-11 flex items-center justify-center" aria-label="{{ __('Capítulo anterior') }}">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                    </button>
                    <span class="text-base font-black tabular-nums min-w-[2.5rem] text-center text-[color:var(--ink)]" x-text="selectedChapter"></span>
                    <button type="button" @click="nextChapter()" class="p-2.5 rounded-lg hover:bg-[color:var(--sacred-parchment-deep)] text-[color:var(--accent)] min-w-11 min-h-11 flex items-center justify-center" aria-label="{{ __('Próximo capítulo') }}">
                        <x-icon name="chevron-right" class="w-5 h-5" />
                    </button>
                </div>

                <select x-model.number="selectedVerse" @change="onVerseSelectChange()"
                        class="w-full sm:w-auto sm:min-w-[6.5rem] text-sm font-bold rounded-xl border border-[color:var(--parchment-edge)] bg-white/85 dark:bg-stone-900/70 text-[color:var(--ink)] px-3 py-2.5 min-h-11 shadow-sm focus:ring-2 focus:ring-[color:var(--accent)]/30 focus:outline-none"
                        title="{{ __('Saltar para versículo') }}">
                    <template x-for="n in verseNumbers()" :key="'vs-' + n">
                        <option :value="n" x-text="'{{ __('Vers.') }} ' + n"></option>
                    </template>
                </select>

                <button type="button" @click="layoutStudy = !layoutStudy"
                        :class="layoutStudy ? 'bg-[color:var(--accent)] text-white border-[color:var(--accent)]' : 'bg-white/60 dark:bg-stone-900/50 text-[color:var(--ink)]'"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-[color:var(--parchment-edge)] text-xs font-black uppercase tracking-wide min-h-11 w-full sm:w-auto">
                    <x-icon name="book-open" class="w-4 h-4 shrink-0" />
                    <span x-text="layoutStudy ? @js(__('Layout clássico')) : @js(__('Modo estudo'))"></span>
                </button>

                <button type="button" @click="isStudyMode = !isStudyMode"
                        :class="isStudyMode ? 'ring-2 ring-[color:var(--sacred-accent)] ring-offset-2 ring-offset-[color:var(--sacred-parchment)]' : ''"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-[color:var(--parchment-edge)] text-xs font-black uppercase tracking-wide bg-[color:var(--sacred-parchment-deep)]/50 text-[color:var(--ink)] min-h-11 w-full sm:w-auto shrink-0">
                    <x-icon name="maximize" class="w-4 h-4 shrink-0" />
                    <span>{{ __('Foco') }}</span>
                </button>

                <div class="flex items-center justify-center gap-1 rounded-xl border border-[color:var(--parchment-edge)] px-2 bg-white/50 dark:bg-black/25 shrink-0 min-h-11"
                     title="{{ __('Tamanho do texto original') }}">
                    <button type="button" @click="originalScaleDown()" class="px-2 py-2 rounded-lg text-[color:var(--accent)] font-black text-sm min-w-10" aria-label="{{ __('Diminuir texto original') }}">A−</button>
                    <span class="text-xs font-black tabular-nums text-[color:var(--ink-muted)] min-w-[2.75rem] text-center" x-text="originalScale + '%'"></span>
                    <button type="button" @click="originalScaleUp()" class="px-2 py-2 rounded-lg text-[color:var(--accent)] font-black text-sm min-w-10" aria-label="{{ __('Aumentar texto original') }}">A+</button>
                </div>

                <div class="flex rounded-xl border border-[color:var(--parchment-edge)] p-0.5 bg-white/50 dark:bg-black/25 shrink-0 justify-center min-h-11 items-center" role="group" aria-label="{{ __('Tom do pergaminho') }}">
                    <button type="button" @click="setPalette('classic')" :class="biblePalette === 'classic' ? 'bg-[color:var(--accent)] text-white' : 'text-[color:var(--ink-muted)]'" class="px-3 py-2 rounded-lg text-[10px] font-black uppercase min-w-10">{{ __('Tom') }} 1</button>
                    <button type="button" @click="setPalette('sepia')" :class="biblePalette === 'sepia' ? 'bg-[color:var(--accent)] text-white' : 'text-[color:var(--ink-muted)]'" class="px-3 py-2 rounded-lg text-[10px] font-black uppercase min-w-10">2</button>
                    <button type="button" @click="setPalette('contrast')" :class="biblePalette === 'contrast' ? 'bg-[color:var(--accent)] text-white' : 'text-[color:var(--ink-muted)]'" class="px-3 py-2 rounded-lg text-[10px] font-black uppercase min-w-10">3</button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-[88rem] mx-auto px-3 sm:px-6 lg:px-10 py-6 sm:py-10">

        <section class="mb-8 interlinear-paper rounded-2xl border-2 border-[color:var(--parchment-edge)] p-4 sm:p-5">
            <button type="button" @click="showCompareTools = !showCompareTools"
                    class="w-full flex items-center justify-between text-left">
                <span class="font-scripture text-sm font-semibold text-[color:var(--ink)] flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-[color:var(--accent)] shrink-0">
                        <x-icon name="book-open" class="w-4 h-4" />
                    </span>
                    {{ __('Comparar traduções (mesma perícope)') }}
                </span>
                <x-icon name="caret-down" class="w-4 h-4 text-[color:var(--ink-muted)] transition-transform shrink-0"
                        x-bind:class="showCompareTools ? 'rotate-180' : ''" />
            </button>
            <div x-show="showCompareTools" x-transition class="mt-4 pt-4 border-t border-[color:var(--parchment-edge)]/50">
                <label class="block text-[10px] font-bold uppercase tracking-widest text-[color:var(--ink-muted)] mb-2">{{ __('Abreviações separadas por vírgula') }}</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" x-model="compareAbbrevs" @keydown.enter.prevent="loadData()"
                           placeholder="Ex.: ARC, TB, NVI, NTLH"
                           class="flex-1 rounded-xl border border-[color:var(--parchment-edge)] bg-white/80 dark:bg-stone-900/50 px-4 py-2.5 text-sm text-[color:var(--ink)] placeholder:text-[color:var(--ink-muted)]/60">
                    <button type="button" @click="loadData()"
                            class="shrink-0 px-5 py-2.5 rounded-xl bg-[color:var(--accent)] text-white text-sm font-bold shadow-md hover:opacity-95 active:scale-[0.99]">
                        {{ __('Aplicar') }}
                    </button>
                </div>
            </div>
        </section>

        <div x-show="loadingData" class="flex flex-col items-center justify-center py-24 interlinear-paper rounded-2xl border-2 border-[color:var(--parchment-edge)]">
            <div class="w-12 h-12 border-[3px] border-[color:var(--accent)] border-t-transparent rounded-full animate-spin mb-4"></div>
            <p class="text-xs font-bold uppercase tracking-widest text-[color:var(--ink-muted)]">{{ __('A abrir capítulo…') }}</p>
        </div>

        <div x-show="!loadingData" x-cloak class="space-y-10 sm:space-y-14">

            <template x-for="(verse, index) in data.verses" :key="'v-' + index">
                <article :id="'interlinear-verse-' + (index + 1)"
                         class="relative interlinear-paper rounded-2xl sm:rounded-3xl border-2 border-[color:var(--parchment-edge)] overflow-hidden shadow-xl ring-1 ring-black/5 dark:ring-white/5 scroll-mt-28">

                    <div class="absolute inset-y-0 left-0 w-1.5 interlinear-scroll-edge opacity-40 pointer-events-none"></div>
                    <div class="absolute inset-y-0 right-0 w-1.5 interlinear-scroll-edge opacity-40 pointer-events-none"></div>

                    <div class="relative px-4 sm:px-8 pt-8 pb-6 sm:pt-10 sm:pb-8">

                        <div class="flex flex-wrap items-baseline justify-between gap-2 mb-6 pb-3 border-b border-[color:var(--parchment-edge)]/60">
                            <h2 class="font-scripture text-xl sm:text-2xl font-bold text-[color:var(--accent)] tracking-wide"
                                x-text="verseReference(index)"></h2>
                            <span class="text-[10px] sm:text-xs uppercase tracking-widest text-[color:var(--ink-muted)]"
                                  x-text="selectedTestament === 'old' ? 'Hebraico (WLC / Strong)' : 'Grego koine (TR / Strong)'"></span>
                        </div>

                        <div :class="layoutStudy && !isCompactScreen ? 'lg:grid lg:grid-cols-2 lg:gap-10 xl:gap-14' : 'space-y-8'">

                            <div class="space-y-3">
                                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-[color:var(--ink-muted)]">{{ __('Texto original') }}</p>
                                <div class="flex flex-wrap gap-x-3 sm:gap-x-5 gap-y-8 sm:gap-y-10 items-end interlinear-original"
                                     :class="selectedTestament === 'old' ? 'flex-row-reverse justify-end text-right' : 'flex-row text-left'"
                                     :style="'font-size: calc(1rem * ' + (originalScale / 100) + ')'">
                                    <template x-for="(segment, sIndex) in verse" :key="'t-' + index + '-' + sIndex">
                                        <div class="relative group/word-pop">
                                            <button type="button"
                                                    @click="showStrong(segment, index)"
                                                    @mouseenter="setHoverKey(index + '-' + sIndex); cancelClearHover()"
                                                    @mouseleave="scheduleClearHover()"
                                                    class="flex flex-col items-center gap-1 max-w-[9rem] sm:max-w-none touch-manipulation rounded-xl p-1 -m-1 transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-600"
                                                    :class="isActiveSegment(segment) ? 'ring-2 ring-amber-600 ring-offset-2 ring-offset-[color:var(--parchment)] dark:ring-offset-stone-900' : ''"
                                                    :title="tooltipForSegment(segment)">

                                                <span class="text-[9px] font-morph font-bold text-[color:var(--gold)]/90 uppercase tracking-tighter opacity-80 group-hover/word-pop:opacity-100"
                                                      x-text="cleanStrong(segment.strong)"></span>

                                                <span class="interlinear-original text-2xl sm:text-3xl lg:text-[2rem] font-scripture font-medium text-[color:var(--ink)] leading-tight px-1 py-0.5 rounded-md group-hover/word-pop:bg-amber-100/50 dark:group-hover/word-pop:bg-amber-900/25 group-hover/word-pop:shadow-sm"
                                                      dir="auto"
                                                      x-text="segment.word"></span>

                                                <span class="text-[11px] sm:text-xs italic text-[color:var(--ink-muted)] font-scripture" x-text="segment.xlit"></span>

                                                <span class="hidden sm:inline font-morph text-[9px] text-[color:var(--ink-muted)]/80 max-w-[7rem] truncate lg:max-w-[9rem]" dir="ltr"
                                                      x-show="layoutStudy || isStudyMode"
                                                      x-text="segment.tag"></span>

                                                <span class="mt-1 px-2 py-0.5 rounded-md bg-[color:var(--accent)]/90 text-white text-[9px] sm:text-[10px] font-bold uppercase tracking-wide shadow-sm max-w-full truncate"
                                                      x-show="segment.lemma_pt"
                                                      x-text="segment.lemma_pt"></span>
                                            </button>
                                            <div x-show="hoverKey === (index + '-' + sIndex)" x-cloak
                                                 @mouseenter="cancelClearHover()"
                                                 @mouseleave="scheduleClearHover()"
                                                 class="absolute z-50 left-1/2 -translate-x-1/2 top-full mt-1 w-[min(18rem,calc(100vw-2rem))] rounded-xl border-2 border-[color:var(--parchment-edge)] bg-[color:var(--sacred-parchment)] dark:bg-stone-900 shadow-xl p-3 text-left">
                                                <p class="text-[10px] font-black uppercase text-[color:var(--gold)]" x-text="cleanStrong(segment.strong)"></p>
                                                <p class="text-xs text-[color:var(--ink)] mt-1 font-scripture leading-snug" x-show="segment.morphology_human_pt" x-text="segment.morphology_human_pt"></p>
                                                <p class="text-[10px] font-morph text-[color:var(--ink-muted)] mt-1 break-all" x-show="segment.tag" x-text="segment.tag"></p>
                                                <p class="text-xs font-semibold text-[color:var(--ink)] mt-2" x-show="segment.lemma_pt" x-text="segment.lemma_pt"></p>
                                                <p class="text-[10px] text-[color:var(--ink-muted)] mt-2">{{ __('Clique para estudo completo') }}</p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="space-y-3 lg:pt-0" :class="layoutStudy && !isCompactScreen ? '' : 'pt-2'">
                                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-[color:var(--ink-muted)]"
                                   x-text="'{{ __('Tradução') }} — ' + (data.translation_version_abbrev || 'PT')"></p>
                                <blockquote class="font-scripture text-base sm:text-lg leading-relaxed text-[color:var(--ink)] border-l-4 border-[color:var(--gold)]/70 pl-4 sm:pl-5 py-1"
                                             :class="layoutStudy ? 'text-lg sm:text-xl lg:text-[1.35rem] leading-[1.65]' : ''"
                                             x-show="data.translation[index]">
                                    <span x-text="data.translation[index]"></span>
                                </blockquote>
                            </div>
                        </div>

                        <template x-if="compareRowsForVerse(index).length">
                            <div class="mt-8 pt-6 border-t border-dashed border-[color:var(--parchment-edge)]">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-[color:var(--ink-muted)] mb-4">{{ __('Outras versões neste versículo') }}</p>
                                <ul class="space-y-3">
                                    <template x-for="entry in compareRowsForVerse(index)" :key="entry[0] + '-' + index">
                                        <li class="flex gap-3 text-sm leading-relaxed">
                                            <span class="shrink-0 font-mono text-[10px] font-black px-2 py-0.5 rounded bg-amber-100/80 dark:bg-amber-900/40 text-[color:var(--accent)] border border-[color:var(--parchment-edge)]" x-text="entry[0]"></span>
                                            <span class="text-[color:var(--ink)] font-scripture" x-text="entry[1]"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </template>
                    </div>
                </article>
            </template>
        </div>
    </main>

    <div class="fixed inset-0 z-[80] pointer-events-none overflow-hidden" x-show="showSidebar" x-cloak>
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px] pointer-events-auto transition-opacity"
             x-show="showSidebar"
             x-transition.opacity
             @click="closePanel()"></div>

        <div class="absolute inset-y-0 right-0 w-full max-w-lg pointer-events-auto flex">
            <div class="w-full h-full interlinear-paper border-l-2 border-[color:var(--parchment-edge)] shadow-2xl overflow-y-auto overscroll-contain"
                 x-show="showSidebar"
                 x-transition:enter="transform transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 role="dialog"
                 aria-modal="true"
                 aria-labelledby="lexicon-title">

                <div class="sticky top-0 z-10 bible-sacred-header backdrop-blur-md border-b border-[color:var(--parchment-edge)] px-5 py-4 space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <h2 id="lexicon-title" class="font-scripture text-base font-bold text-[color:var(--ink)]">{{ __('Estudo da palavra') }}</h2>
                        <button type="button" @click="closePanel()" class="p-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 text-[color:var(--ink-muted)]" aria-label="{{ __('Fechar') }}">
                            <x-icon name="xmark" class="w-5 h-5" />
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-1.5" role="tablist">
                        <button type="button" role="tab" @click="switchStudyTab('word')"
                                :class="studyTab === 'word' ? 'bg-[color:var(--accent)] text-white' : 'bg-white/60 dark:bg-stone-800 text-[color:var(--ink-muted)]'"
                                class="px-2.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wide">{{ __('Palavra') }}</button>
                        <button type="button" role="tab" @click="switchStudyTab('lexicon')"
                                :class="studyTab === 'lexicon' ? 'bg-[color:var(--accent)] text-white' : 'bg-white/60 dark:bg-stone-800 text-[color:var(--ink-muted)]'"
                                class="px-2.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wide">{{ __('Léxico') }}</button>
                        <button type="button" role="tab" @click="switchStudyTab('occurrences')"
                                :class="studyTab === 'occurrences' ? 'bg-[color:var(--accent)] text-white' : 'bg-white/60 dark:bg-stone-800 text-[color:var(--ink-muted)]'"
                                class="px-2.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wide">{{ __('Ocorrências') }}</button>
                        <button type="button" role="tab" @click="switchStudyTab('refs')"
                                :class="studyTab === 'refs' ? 'bg-[color:var(--accent)] text-white' : 'bg-white/60 dark:bg-stone-800 text-[color:var(--ink-muted)]'"
                                class="px-2.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wide">{{ __('Refs.') }}</button>
                        <button type="button" role="tab" @click="switchStudyTab('commentary')"
                                :class="studyTab === 'commentary' ? 'bg-[color:var(--accent)] text-white' : 'bg-white/60 dark:bg-stone-800 text-[color:var(--ink-muted)]'"
                                class="px-2.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wide">{{ __('Comentários') }}</button>
                        <button type="button" role="tab" @click="switchStudyTab('links')"
                                :class="studyTab === 'links' ? 'bg-[color:var(--accent)] text-white' : 'bg-white/60 dark:bg-stone-800 text-[color:var(--ink-muted)]'"
                                class="px-2.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wide">{{ __('Ligações') }}</button>
                    </div>
                </div>

                <div class="p-5 sm:p-6 space-y-6 pb-24">

                    <div x-show="studyTab === 'word'">
                        <template x-if="clickedSegment">
                            <div class="space-y-4">
                                <div class="rounded-2xl border-2 border-[color:var(--parchment-edge)] p-5 bg-white/40 dark:bg-black/20">
                                    <p class="font-scripture text-3xl text-[color:var(--ink)]" dir="auto" x-text="clickedSegment.word"></p>
                                    <p class="text-sm font-mono text-[color:var(--gold)] mt-2" x-text="cleanStrong(clickedSegment.strong)"></p>
                                </div>
                                <div class="rounded-xl border border-[color:var(--parchment-edge)] px-4 py-3 bg-amber-50/50 dark:bg-amber-950/20">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-[color:var(--ink-muted)] mb-1">{{ __('Morfologia (resumo)') }}</p>
                                    <p class="text-sm text-[color:var(--ink)] font-scripture" x-text="clickedSegment.morphology_human_pt || '—'"></p>
                                    <p class="font-morph text-[10px] text-[color:var(--ink-muted)] mt-2 break-all" x-show="clickedSegment.tag" x-text="clickedSegment.tag"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="studyTab === 'lexicon'">
                        <div x-show="loadingStrong" class="py-16 text-center">
                            <div class="w-10 h-10 border-[3px] border-[color:var(--accent)] border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                            <p class="text-xs font-bold uppercase tracking-widest text-[color:var(--ink-muted)]">{{ __('A carregar entrada…') }}</p>
                        </div>
                        <template x-if="!loadingStrong && strongDef">
                            <div class="space-y-6 animate-fade-in">
                                <div class="rounded-2xl border-2 border-[color:var(--parchment-edge)] p-5 bg-white/40 dark:bg-black/20">
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded bg-amber-100/90 dark:bg-amber-900/50 text-[color:var(--accent)]"
                                              x-text="selectedTestament === 'old' ? 'Hebraico' : 'Grego'"></span>
                                        <span class="font-mono text-lg font-black text-[color:var(--gold)]" x-text="strongDef?.number"></span>
                                    </div>
                                    <p class="font-scripture text-3xl sm:text-4xl text-[color:var(--ink)] mb-2 break-words" dir="auto" x-text="strongDef?.lemma"></p>
                                    <div class="flex flex-wrap gap-x-3 gap-y-1 text-sm text-[color:var(--ink-muted)]">
                                        <span class="italic font-scripture" x-text="strongDef?.xlit"></span>
                                        <span class="text-[color:var(--parchment-edge)]">·</span>
                                        <span class="font-medium" x-text="strongDef?.pronounce"></span>
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-[color:var(--parchment-edge)] p-5 bg-white/30 dark:bg-black/15" x-show="semanticHeadline()">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-[color:var(--ink-muted)] mb-2">{{ __('Equivalente semântico') }}</p>
                                    <p class="font-scripture text-lg font-semibold text-[color:var(--ink)] leading-snug" x-text="semanticHeadline()"></p>
                                </div>
                                <div class="rounded-2xl border border-[color:var(--parchment-edge)] p-5 bg-emerald-50/30 dark:bg-emerald-950/15" x-show="strongPtSuggested">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-[color:var(--ink-muted)] mb-2" x-text="'{{ __('Sugestão no contexto') }} (' + (data.translation_version_abbrev || 'PT') + ')'"></p>
                                    <p class="font-scripture text-lg font-semibold text-[color:var(--ink)]" x-text="strongPtSuggested"></p>
                                </div>
                                <div>
                                    <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-[color:var(--ink-muted)] mb-3 flex items-center gap-2">
                                        <span class="h-px flex-1 bg-[color:var(--parchment-edge)]/50"></span>
                                        {{ __('Significado e uso') }}
                                        <span class="h-px flex-1 bg-[color:var(--parchment-edge)]/50"></span>
                                    </h3>
                                    <template x-if="strongDef?.meaning_usage_pt">
                                        <p class="font-scripture text-[color:var(--ink)] leading-relaxed mb-4 font-medium" x-text="strongDef.meaning_usage_pt"></p>
                                    </template>
                                    <div class="prose prose-sm prose-stone dark:prose-invert max-w-none font-scripture text-[color:var(--ink-muted)] leading-relaxed"
                                         x-html="strongDef ? formatDef(strongDef.description || '') : ''"></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="studyTab === 'occurrences'">
                        <div x-show="loadingOccurrences" class="py-12 text-center text-sm text-[color:var(--ink-muted)]">{{ __('A carregar…') }}</div>
                        <template x-if="!loadingOccurrences && occurrencesData">
                            <div class="space-y-3">
                                <p class="text-sm font-bold text-[color:var(--ink)]">
                                    <span x-text="occurrencesData.total"></span> {{ __('ocorrências na base interlinear') }}
                                </p>
                                <ul class="space-y-2 text-sm">
                                    <template x-for="(ref, ri) in occurrencesData.sample" :key="'oc-' + ri">
                                        <li>
                                            <a :href="interlinearPublicUrl(ref)" class="text-[color:var(--accent)] font-semibold underline decoration-dotted hover:opacity-90">
                                                <span x-text="(ref.book_english || '') + ' ' + ref.chapter + ':' + ref.verse"></span>
                                            </a>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </template>
                    </div>

                    <div x-show="studyTab === 'refs'">
                        <template x-if="drawerCrossRefs().length">
                            <ul class="space-y-3 text-sm">
                                <template x-for="(ref, ri) in drawerCrossRefs()" :key="'xr-' + ri">
                                    <li class="rounded-xl border border-[color:var(--parchment-edge)] p-3 bg-white/30 dark:bg-black/15">
                                        <a :href="interlinearPublicUrl(ref)" class="font-bold text-[color:var(--accent)] underline decoration-dotted">
                                            <span x-text="(ref.to_book_english || '') + ' ' + ref.to_chapter + ':' + ref.to_verse"></span>
                                        </a>
                                        <p class="text-[color:var(--ink-muted)] text-xs mt-1" x-show="ref.note_pt" x-text="ref.note_pt"></p>
                                    </li>
                                </template>
                            </ul>
                        </template>
                        <p x-show="!drawerCrossRefs().length" class="text-sm text-[color:var(--ink-muted)]">{{ __('Sem referências cruzadas importadas para este versículo.') }}</p>
                    </div>

                    <div x-show="studyTab === 'commentary'">
                        <template x-if="drawerCommentary().length">
                            <div class="space-y-4">
                                <template x-for="(c, ci) in drawerCommentary()" :key="'cm-' + ci">
                                    <div class="rounded-xl border border-[color:var(--parchment-edge)] p-4 bg-white/30 dark:bg-black/15">
                                        <p class="text-[10px] font-black uppercase text-[color:var(--gold)] mb-2" x-text="c.source_title || c.source_slug"></p>
                                        <div class="prose prose-sm prose-stone dark:prose-invert max-w-none font-scripture text-[color:var(--ink)] whitespace-pre-wrap" x-text="c.body"></div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <p x-show="!drawerCommentary().length" class="text-sm text-[color:var(--ink-muted)]">{{ __('Sem comentários importados para este versículo.') }}</p>
                    </div>

                    <div x-show="studyTab === 'links'">
                        <ul class="space-y-2">
                            <template x-for="(lnk, li) in externalStudyLinks()" :key="'lnk-' + li">
                                <li>
                                    <a :href="lnk.href" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-2 text-[color:var(--accent)] font-semibold underline decoration-dotted hover:opacity-90">
                                        <span x-text="lnk.label"></span>
                                        <x-icon name="arrow-up-right-from-square" class="w-3.5 h-3.5 opacity-70" />
                                    </a>
                                </li>
                            </template>
                        </ul>
                        <p x-show="externalStudyLinks().length === 0" class="text-sm text-[color:var(--ink-muted)] mt-2">{{ __('Sem ligações externas para este código Strong.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
