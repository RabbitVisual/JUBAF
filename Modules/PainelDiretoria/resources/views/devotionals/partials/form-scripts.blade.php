{{-- @var string $routePrefix @var bool $bibleEnabled --}}
@php
    $bibleEnabled = $bibleEnabled ?? false;
@endphp
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cfg = {
            bibleEnabled: @json($bibleEnabled),
            fetchScriptureUrl: @json(route($routePrefix . '.fetch-scripture')),
            bibleBooksUrl: @json(route($routePrefix . '.bible-books')),
            bibleChaptersUrl: @json(route($routePrefix . '.bible-chapters')),
            csrf: @json(csrf_token()),
        };

        const type = document.getElementById('author_type');
        const boxUser = document.getElementById('box-user');
        const boxBoard = document.getElementById('box-board');
        const boxGuest = document.getElementById('box-guest');

        function syncAuthor() {
            if (!type) return;
            const v = type.value;
            const USER = @json(\App\Models\Devotional::AUTHOR_USER);
            const BOARD = @json(\App\Models\Devotional::AUTHOR_BOARD_MEMBER);
            const GUEST = @json(\App\Models\Devotional::AUTHOR_PASTOR_GUEST);
            if (boxUser) boxUser.classList.toggle('hidden', v !== USER);
            if (boxBoard) boxBoard.classList.toggle('hidden', v !== BOARD);
            if (boxGuest) boxGuest.classList.toggle('hidden', v !== GUEST);
        }
        if (type) {
            type.addEventListener('change', syncAuthor);
            syncAuthor();
        }

        const elTitle = document.getElementById('devotional-title');
        const elDate = document.getElementById('devotional-date');
        const elRef = document.getElementById('scripture_reference');
        const elScripture = document.getElementById('scripture_text');
        const elBody = document.getElementById('devotional-body');
        const elCover = document.getElementById('devotional-cover-input');
        const pvDate = document.getElementById('devotional-preview-date');
        const pvTitle = document.getElementById('devotional-preview-title');
        const pvRef = document.getElementById('devotional-preview-ref');
        const pvScripture = document.getElementById('devotional-preview-scripture');
        const pvBody = document.getElementById('devotional-preview-body');
        const pvCoverWrap = document.getElementById('devotional-preview-cover-wrap');
        const pvCoverImg = document.getElementById('devotional-preview-cover-img');

        function fmtDate(iso) {
            if (!iso) return '';
            const d = new Date(iso + 'T12:00:00');
            if (Number.isNaN(d.getTime())) return '';
            return d.toLocaleDateString('pt-PT', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        function syncPreview() {
            if (pvTitle) pvTitle.textContent = (elTitle && elTitle.value.trim()) || 'Título do devocional';
            if (pvDate) pvDate.textContent = elDate && elDate.value ? fmtDate(elDate.value) : 'Data a definir';
            if (pvRef) pvRef.textContent = (elRef && elRef.value.trim()) ? elRef.value.trim() : 'Referência bíblica';
            if (pvScripture) {
                const t = elScripture ? elScripture.value.trim() : '';
                pvScripture.textContent = t || 'O texto da passagem aparecerá aqui.';
            }
            if (pvBody) {
                const b = elBody ? elBody.value.trim() : '';
                pvBody.textContent = b || 'A reflexão aparecerá aqui.';
            }
        }

        ['input', 'change'].forEach(ev => {
            [elTitle, elDate, elRef, elScripture, elBody].forEach(el => {
                if (el) el.addEventListener(ev, syncPreview);
            });
        });
        syncPreview();

        if (elCover && pvCoverWrap && pvCoverImg) {
            elCover.addEventListener('change', function() {
                const f = elCover.files && elCover.files[0];
                if (!f) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    pvCoverImg.src = e.target.result;
                    pvCoverWrap.classList.remove('hidden');
                };
                reader.readAsDataURL(f);
            });
        }

        (function() {
            const block = document.getElementById('devotional-video-block');
            if (!block) return;

            const initial = block.dataset.initialMode || 'none';
            const btns = block.querySelectorAll('[data-video-mode]');
            const panelNone = document.getElementById('video-panel-none');
            const panelFile = document.getElementById('video-panel-file');
            const panelUrl = document.getElementById('video-panel-url');
            const inputFile = document.getElementById('devotional-video-input');
            const inputUrl = document.getElementById('devotional-video-url');
            const clearFlag = document.getElementById('clear_devotional_video');
            const form = block.closest('form');
            const fileNameEl = document.getElementById('devotional-video-filename');

            const baseBtn =
                'video-mode-btn flex items-center justify-center gap-2 rounded-xl border-2 px-3 py-2.5 text-xs font-semibold transition';
            const activeBtn =
                'border-emerald-500 bg-emerald-50 text-emerald-900 shadow-sm dark:border-emerald-500 dark:bg-emerald-950/50 dark:text-emerald-100';
            const idleBtn =
                'border-transparent bg-gray-100/90 text-gray-700 hover:bg-gray-200/90 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700';

            let mode = initial;

            function applyModeVisual() {
                btns.forEach(function(btn) {
                    const m = btn.getAttribute('data-video-mode');
                    const on = m === mode;
                    btn.setAttribute('aria-checked', on ? 'true' : 'false');
                    btn.className = baseBtn + ' ' + (on ? activeBtn : idleBtn);
                });
                if (panelNone) panelNone.classList.toggle('hidden', mode !== 'none');
                if (panelFile) panelFile.classList.toggle('hidden', mode !== 'file');
                if (panelUrl) panelUrl.classList.toggle('hidden', mode !== 'url');
                if (clearFlag) clearFlag.value = mode === 'none' ? '1' : '0';
            }

            function setMode(m) {
                mode = m;
                applyModeVisual();
            }

            btns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const m = btn.getAttribute('data-video-mode');
                    if (m === 'file' && inputUrl) inputUrl.value = '';
                    if (m === 'url' && inputFile) {
                        inputFile.value = '';
                        if (fileNameEl) {
                            fileNameEl.textContent = '';
                            fileNameEl.classList.add('hidden');
                        }
                    }
                    if (m === 'none') {
                        if (inputFile) {
                            inputFile.value = '';
                        }
                        if (inputUrl) inputUrl.value = '';
                        if (fileNameEl) {
                            fileNameEl.textContent = '';
                            fileNameEl.classList.add('hidden');
                        }
                    }
                    setMode(m);
                });
            });

            if (inputFile) {
                inputFile.addEventListener('change', function() {
                    const f = inputFile.files && inputFile.files[0];
                    if (f && fileNameEl) {
                        fileNameEl.textContent = 'Seleccionado: ' + f.name;
                        fileNameEl.classList.remove('hidden');
                    }
                });
            }

            if (form) {
                form.addEventListener('submit', function() {
                    if (mode === 'file' && inputUrl) inputUrl.value = '';
                    if (mode === 'url' && inputFile) inputFile.value = '';
                    if (mode === 'none') {
                        if (inputFile) inputFile.value = '';
                        if (inputUrl) inputUrl.value = '';
                        if (clearFlag) clearFlag.value = '1';
                    }
                });
            }

            setMode(initial);
        })();

        const btnFetch = document.getElementById('btn-fetch-scripture');
        if (btnFetch && elRef) {
            btnFetch.addEventListener('click', function() {
                const ref = elRef.value;
                const vidEl = document.getElementById('bible_version_id');
                const vid = vidEl && vidEl.value ? vidEl.value : '';
                const msg = document.getElementById('fetch-scripture-msg');
                if (msg) {
                    msg.textContent = 'A carregar…';
                    msg.className = 'mt-2 text-xs text-gray-500 dark:text-slate-500';
                }
                fetch(cfg.fetchScriptureUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': cfg.csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        ref: ref,
                        version_id: vid ? parseInt(vid, 10) : null
                    }),
                }).then(r => r.json()).then(data => {
                    if (data.ok && data.data) {
                        if (elScripture) elScripture.value = data.data.plain_text;
                        if (data.data.bible_version_id && vidEl) vidEl.value = String(data.data.bible_version_id);
                        if (elRef && data.data.reference) elRef.value = data.data.reference;
                        if (msg) {
                            msg.textContent = 'Texto carregado (' + data.data.reference + ').';
                            msg.className = 'mt-2 text-xs text-emerald-600 dark:text-emerald-400';
                        }
                        syncPreview();
                    } else {
                        if (msg) {
                            msg.textContent = data.message || 'Erro.';
                            msg.className = 'mt-2 text-xs text-red-600 dark:text-red-400';
                        }
                    }
                }).catch(() => {
                    if (msg) {
                        msg.textContent = 'Erro de rede.';
                        msg.className = 'mt-2 text-xs text-red-600 dark:text-red-400';
                    }
                });
            });
        }

        if (!cfg.bibleEnabled) return;

        const versionEl = document.getElementById('bible_version_id');
        const bookSel = document.getElementById('picker_book');
        const chapterSel = document.getElementById('picker_chapter');
        const verseStart = document.getElementById('verse_start');
        const verseEnd = document.getElementById('verse_end');
        const btnBuild = document.getElementById('btn-build-ref');

        let booksCache = [];
        let initialRefToSync = elRef && elRef.value.trim() ? elRef.value.trim() : null;

        function parseReference(ref) {
            const m = ref.trim().match(/^(.+?)\s+(\d+):(\d+)(?:-(\d+))?$/u);
            if (!m) return null;
            return {
                book: m[1].trim(),
                chapter: parseInt(m[2], 10),
                v1: parseInt(m[3], 10),
                v2: m[4] ? parseInt(m[4], 10) : parseInt(m[3], 10)
            };
        }

        function applyVerseMaxFromChapterOption() {
            if (!chapterSel) return;
            const opt = chapterSel.options[chapterSel.selectedIndex];
            const maxV = opt && opt.dataset.totalVerses ? parseInt(opt.dataset.totalVerses, 10) : null;
            if (maxV) {
                if (verseStart) verseStart.max = maxV;
                if (verseEnd) verseEnd.max = maxV;
            }
        }

        async function loadChapters(bookId, syncParsed) {
            if (!chapterSel) return;
            if (!bookId) {
                chapterSel.innerHTML = '<option value="">—</option>';
                chapterSel.disabled = true;
                return;
            }
            chapterSel.innerHTML = '<option value="">A carregar…</option>';
            chapterSel.disabled = true;
            const url = cfg.bibleChaptersUrl + '?book_id=' + encodeURIComponent(bookId);
            try {
                const r = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await r.json();
                if (!data.ok || !data.data) {
                    chapterSel.innerHTML = '<option value="">Erro</option>';
                    return;
                }
                chapterSel.innerHTML = '<option value="">Capítulo</option>';
                data.data.forEach(function(ch) {
                    const opt = document.createElement('option');
                    opt.value = String(ch.chapter_number);
                    opt.textContent = 'Cap. ' + ch.chapter_number;
                    opt.dataset.totalVerses = ch.total_verses || '';
                    chapterSel.appendChild(opt);
                });
                chapterSel.disabled = false;
                if (syncParsed) {
                    chapterSel.value = String(syncParsed.chapter);
                    applyVerseMaxFromChapterOption();
                    if (verseStart) verseStart.value = String(syncParsed.v1);
                    if (verseEnd) verseEnd.value = String(syncParsed.v2);
                }
            } catch (e) {
                chapterSel.innerHTML = '<option value="">Erro</option>';
            }
        }

        async function loadBooks() {
            if (!bookSel) return;
            const vid = versionEl && versionEl.value ? versionEl.value : '';
            let url = cfg.bibleBooksUrl;
            if (vid) url += (url.includes('?') ? '&' : '?') + 'version_id=' + encodeURIComponent(vid);
            bookSel.innerHTML = '<option value="">A carregar livros…</option>';
            bookSel.disabled = true;
            try {
                const r = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await r.json();
                if (!data.ok || !data.data) {
                    bookSel.innerHTML = '<option value="">Erro ao carregar livros</option>';
                    return;
                }
                booksCache = data.data;
                bookSel.innerHTML = '<option value="">Escolha o livro</option>';
                data.data.forEach(function(b) {
                    const opt = document.createElement('option');
                    opt.value = String(b.id);
                    opt.textContent = b.name;
                    opt.dataset.name = b.name;
                    opt.dataset.testament = b.testament || '';
                    bookSel.appendChild(opt);
                });
                bookSel.disabled = false;

                const refSnap = initialRefToSync;
                if (refSnap) {
                    initialRefToSync = null;
                    const p = parseReference(refSnap);
                    if (p) {
                        const match = booksCache.find(function(b) {
                            return b.name.trim() === p.book || b.name.toLowerCase() === p.book.toLowerCase();
                        });
                        if (match) {
                            bookSel.value = String(match.id);
                            await loadChapters(String(match.id), p);
                        }
                    }
                }
            } catch (e) {
                bookSel.innerHTML = '<option value="">Erro de rede</option>';
            }
        }

        if (versionEl) {
            versionEl.addEventListener('change', function() {
                initialRefToSync = null;
                loadBooks();
            });
        }
        if (bookSel) {
            bookSel.addEventListener('change', function() {
                loadChapters(bookSel.value, null);
            });
        }
        if (chapterSel) {
            chapterSel.addEventListener('change', function() {
                const opt = chapterSel.options[chapterSel.selectedIndex];
                const maxV = opt && opt.dataset.totalVerses ? parseInt(opt.dataset.totalVerses, 10) : null;
                if (maxV) {
                    if (verseStart) {
                        verseStart.max = maxV;
                        if (parseInt(verseStart.value, 10) > maxV) verseStart.value = String(maxV);
                    }
                    if (verseEnd) {
                        verseEnd.max = maxV;
                        if (parseInt(verseEnd.value, 10) > maxV) verseEnd.value = String(maxV);
                    }
                }
            });
        }

        if (btnBuild && bookSel && chapterSel && elRef) {
            btnBuild.addEventListener('click', function() {
                const bid = bookSel.value;
                const chNum = chapterSel.value;
                const opt = bookSel.options[bookSel.selectedIndex];
                const bookName = opt && opt.dataset.name ? opt.dataset.name : (opt ? opt.textContent : '');
                if (!bid || !chNum || !bookName) {
                    alert('Escolha livro e capítulo.');
                    return;
                }
                let v1 = verseStart ? parseInt(verseStart.value, 10) : 1;
                let v2 = verseEnd ? parseInt(verseEnd.value, 10) : v1;
                if (Number.isNaN(v1) || v1 < 1) v1 = 1;
                if (Number.isNaN(v2) || v2 < 1) v2 = v1;
                if (v2 < v1) v2 = v1;
                let ref = bookName + ' ' + chNum + ':' + v1;
                if (v2 !== v1) ref += '-' + v2;
                elRef.value = ref;
                syncPreview();
            });
        }

        loadBooks();
    });
</script>
