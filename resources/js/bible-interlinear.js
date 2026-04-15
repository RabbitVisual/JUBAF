/**
 * Alpine.js: leitor interlinear público / painel (Bíblia).
 * Requer window.__interlinearConfig (definido na vista Blade).
 */
export function registerBibleInterlinear(Alpine) {
    Alpine.data('interlinearApp', () => ({
        panelMode: false,
        backUrl: '',
        backLabel: '',
        publicBibleIndexUrl: '/',

        bibleVersions: [],
        allBooks: [],
        books: [],

        selectedTestament: 'old',
        selectedVersionAbbrev: '',
        selectedBook: '',
        selectedChapter: 1,
        selectedVerse: 1,

        layoutStudy: false,
        isStudyMode: false,
        isCompactScreen: false,

        biblePalette: 'classic',
        originalScale: 100,

        showCompareTools: false,
        compareAbbrevs: '',

        loadingData: false,
        interlinearError: '',

        data: {
            verses: [],
            translation: [],
            translation_version_abbrev: '',
            verse_study: [],
            translations_compare: {},
            book: '',
            book_display_pt: '',
            chapter: 1,
        },

        scrollProgress: 0,

        showSidebar: false,
        studyTab: 'word',
        clickedSegment: null,
        clickedVerseIndex: null,

        strongDef: null,
        loadingStrong: false,
        strongPtSuggested: '',

        occurrencesData: null,
        loadingOccurrences: false,

        hoverKey: '',
        _hoverClearTimer: null,

        cfg() {
            return window.__interlinearConfig || {};
        },

        async init() {
            const c = this.cfg();
            this.panelMode = !!c.panelMode;
            this.backUrl = c.backUrl || '';
            this.backLabel = c.backLabel || '';
            this.publicBibleIndexUrl = c.publicBibleIndexUrl || '/';
            this.bibleVersions = Array.isArray(c.bibleVersions) ? c.bibleVersions : [];

            const savedPalette = localStorage.getItem('bible_public_palette');
            if (savedPalette === 'classic' || savedPalette === 'sepia' || savedPalette === 'contrast') {
                this.biblePalette = savedPalette;
            }

            const u = new URL(window.location.href);
            const qt = u.searchParams.get('testament');
            if (qt === 'old' || qt === 'new') {
                this.selectedTestament = qt;
            }
            const qb = u.searchParams.get('book');
            if (qb) {
                this.selectedBook = qb;
            }
            const qc = u.searchParams.get('chapter');
            if (qc) {
                const n = parseInt(qc, 10);
                if (!Number.isNaN(n) && n > 0) {
                    this.selectedChapter = n;
                }
            }

            if (this.bibleVersions.length && !this.selectedVersionAbbrev) {
                this.selectedVersionAbbrev = this.bibleVersions[0].abbreviation;
            }

            await this.loadBooks();
            if (this.selectedBook) {
                await this.loadData();
            }

            this.updateCompact();
            window.addEventListener('resize', () => this.updateCompact());
        },

        updateCompact() {
            this.isCompactScreen = window.matchMedia('(max-width: 1023px)').matches;
        },

        filterBooksForTestament() {
            const t = this.selectedTestament;
            const filtered = this.allBooks.filter((b) => b.testament === t);
            this.books = filtered;
            if (!filtered.length) {
                this.selectedBook = '';
                return;
            }
            if (!filtered.some((b) => b.name === this.selectedBook)) {
                this.selectedBook = filtered[0].name;
            }
        },

        maxChapterForBook() {
            const b = this.allBooks.find((x) => x.name === this.selectedBook);
            const n = b?.total_chapters;
            return typeof n === 'number' && n > 0 ? n : 150;
        },

        async loadBooks() {
            const booksUrl = this.cfg().routes?.books;
            if (!booksUrl) {
                return;
            }
            try {
                const r = await fetch(booksUrl, { headers: { Accept: 'application/json' } });
                const json = await r.json();
                this.allBooks = Array.isArray(json) ? json : [];
                this.filterBooksForTestament();
            } catch (e) {
                this.allBooks = [];
                this.books = [];
            }
        },

        loadChapters() {
            this.selectedChapter = 1;
            return this.loadData();
        },

        async loadData() {
            const c = this.cfg();
            const dataUrl = c.routes?.data;
            if (!dataUrl || !this.selectedBook) {
                return;
            }

            this.loadingData = true;
            this.interlinearError = '';

            const params = new URLSearchParams({
                book: this.selectedBook,
                chapter: String(this.selectedChapter),
                testament: this.selectedTestament,
                version: this.selectedVersionAbbrev || '',
                compare: this.compareAbbrevs || '',
            });

            try {
                const r = await fetch(`${dataUrl}?${params.toString()}`, {
                    headers: { Accept: 'application/json' },
                });
                const json = await r.json();

                if (!r.ok || json.error) {
                    this.interlinearError = json.message || c.loadErrorMessage || 'Erro ao carregar.';
                    this.data = {
                        verses: [],
                        translation: [],
                        translation_version_abbrev: '',
                        verse_study: [],
                        translations_compare: {},
                        book: '',
                        book_display_pt: '',
                        chapter: this.selectedChapter,
                    };
                } else {
                    this.data = {
                        verses: json.verses || [],
                        translation: json.translation || [],
                        translation_version_abbrev: json.translation_version_abbrev || '',
                        verse_study: json.verse_study || [],
                        translations_compare: json.translations_compare || {},
                        book: json.book || '',
                        book_display_pt: json.book_display_pt || '',
                        chapter: json.chapter || this.selectedChapter,
                    };
                    if (json.testament === 'old' || json.testament === 'new') {
                        this.selectedTestament = json.testament;
                    }
                    const nv = this.data.verses.length;
                    if (nv < 1) {
                        this.selectedVerse = 1;
                    } else if (this.selectedVerse > nv) {
                        this.selectedVerse = nv;
                    }
                }
            } catch (e) {
                this.interlinearError = c.loadErrorMessage || 'Erro de rede.';
                this.data = {
                    verses: [],
                    translation: [],
                    translation_version_abbrev: '',
                    verse_study: [],
                    translations_compare: {},
                    book: '',
                    book_display_pt: '',
                    chapter: this.selectedChapter,
                };
            } finally {
                this.loadingData = false;
            }
        },

        prevChapter() {
            if (this.selectedChapter > 1) {
                this.selectedChapter -= 1;
                this.loadData();
            }
        },

        nextChapter() {
            const max = this.maxChapterForBook();
            if (this.selectedChapter < max) {
                this.selectedChapter += 1;
                this.loadData();
            }
        },

        verseNumbers() {
            const n = this.data.verses?.length || 0;
            return Array.from({ length: n }, (_, i) => i + 1);
        },

        verseReference(index) {
            const v = index + 1;
            const bookPt = this.data.book_display_pt || this.data.book || '';
            const ch = this.data.chapter || this.selectedChapter;
            return `${bookPt} ${ch}:${v}`;
        },

        compareRowsForVerse(index) {
            const cmp = this.data.translations_compare || {};
            const rows = [];
            for (const [abbr, verses] of Object.entries(cmp)) {
                if (!Array.isArray(verses)) {
                    continue;
                }
                const text = verses[index];
                if (text) {
                    rows.push([abbr, text]);
                }
            }
            return rows;
        },

        updateProgress() {
            const doc = document.documentElement;
            const st = window.scrollY ?? doc.scrollTop;
            const max = doc.scrollHeight - doc.clientHeight;
            this.scrollProgress = max > 0 ? Math.min(100, Math.max(0, (st / max) * 100)) : 0;
        },

        setPalette(p) {
            if (p !== 'classic' && p !== 'sepia' && p !== 'contrast') {
                return;
            }
            this.biblePalette = p;
            try {
                localStorage.setItem('bible_public_palette', p);
            } catch (e) { /* ignore */ }
        },

        originalScaleDown() {
            this.originalScale = Math.max(70, this.originalScale - 10);
        },

        originalScaleUp() {
            this.originalScale = Math.min(160, this.originalScale + 10);
        },

        cleanStrong(raw) {
            if (raw == null) {
                return '';
            }
            const s = String(raw).trim();
            return s.replace(/\s+/g, '');
        },

        isActiveSegment(segment) {
            if (!this.clickedSegment || !segment) {
                return false;
            }
            return this.clickedSegment.strong === segment.strong && this.clickedSegment.word === segment.word;
        },

        tooltipForSegment(segment) {
            const st = this.cleanStrong(segment?.strong);
            return st ? `Strong ${st}` : '';
        },

        setHoverKey(key) {
            this.hoverKey = key;
        },

        scheduleClearHover() {
            if (this._hoverClearTimer) {
                clearTimeout(this._hoverClearTimer);
            }
            this._hoverClearTimer = setTimeout(() => {
                this.hoverKey = '';
                this._hoverClearTimer = null;
            }, 180);
        },

        cancelClearHover() {
            if (this._hoverClearTimer) {
                clearTimeout(this._hoverClearTimer);
                this._hoverClearTimer = null;
            }
        },

        showStrong(segment, verseIndex) {
            this.clickedSegment = segment;
            this.clickedVerseIndex = verseIndex;
            this.strongPtSuggested = segment?.pt_suggested || '';
            this.showSidebar = true;
            this.studyTab = 'word';
            this.strongDef = null;
            this.occurrencesData = null;
            const sn = this.cleanStrong(segment?.strong);
            if (sn) {
                this.fetchStrong(sn);
                this.fetchOccurrences(sn);
            }
        },

        async fetchStrong(number) {
            const prefix = this.cfg().routes?.strongPrefix;
            if (!prefix) {
                return;
            }
            this.loadingStrong = true;
            try {
                const r = await fetch(prefix + encodeURIComponent(number), {
                    headers: { Accept: 'application/json' },
                });
                if (r.ok) {
                    this.strongDef = await r.json();
                } else {
                    this.strongDef = null;
                }
            } catch (e) {
                this.strongDef = null;
            } finally {
                this.loadingStrong = false;
            }
        },

        async fetchOccurrences(number) {
            const prefix = this.cfg().routes?.occurrencesPrefix;
            if (!prefix) {
                return;
            }
            this.loadingOccurrences = true;
            try {
                const r = await fetch(prefix + encodeURIComponent(number), {
                    headers: { Accept: 'application/json' },
                });
                if (r.ok) {
                    this.occurrencesData = await r.json();
                } else {
                    this.occurrencesData = null;
                }
            } catch (e) {
                this.occurrencesData = null;
            } finally {
                this.loadingOccurrences = false;
            }
        },

        closePanel() {
            this.showSidebar = false;
        },

        switchStudyTab(tab) {
            this.studyTab = tab;
        },

        drawerCrossRefs() {
            const idx = this.clickedVerseIndex;
            if (idx === null || idx === undefined) {
                return [];
            }
            const vs = this.data.verse_study?.[idx];
            return vs?.cross_references || [];
        },

        drawerCommentary() {
            const idx = this.clickedVerseIndex;
            if (idx === null || idx === undefined) {
                return [];
            }
            const vs = this.data.verse_study?.[idx];
            return vs?.commentary || [];
        },

        interlinearPublicUrl(ref) {
            const base = this.cfg().publicInterlinearPath || '';
            const book = ref?.book_english || ref?.book || 'Genesis';
            const chapter = ref?.chapter ?? 1;
            const verse = ref?.verse ?? 1;
            const testament = ref?.testament || 'old';
            const q = new URLSearchParams({ book, chapter: String(chapter), testament });
            const url = base + (base.includes('?') ? '&' : '?') + q.toString();
            return `${url}#interlinear-verse-${verse}`;
        },

        externalStudyLinks() {
            const n = this.cleanStrong(this.clickedSegment?.strong);
            if (!n) {
                return [];
            }
            const lower = n.toLowerCase();
            return [
                {
                    label: 'Blue Letter Bible',
                    href: `https://www.blueletterbible.org/lexicon/${encodeURIComponent(lower)}/kjv/wlc/0-1/`,
                },
            ];
        },

        formatDef(html) {
            if (!html) {
                return '';
            }
            return String(html)
                .replace(/\n/g, '<br>');
        },

        semanticHeadline() {
            return this.strongDef?.semantic_equivalent_pt || '';
        },

        onVerseSelectChange() {
            const id = `interlinear-verse-${this.selectedVerse}`;
            const el = document.getElementById(id);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        },
    }));
}
