/**
 * Alpine: página índice da Bíblia pública (continuar a ler).
 * Requer window.__biblePublicIndexConfig.chapterUrlTemplate (definido na vista).
 * Última leitura: localStorage bible_public_last_chapter (JSON), escrito na vista do capítulo.
 */
const STORAGE_KEY = 'bible_public_last_chapter';

function parseLast() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) {
            return null;
        }
        const o = JSON.parse(raw);
        if (
            o &&
            o.versionAbbr &&
            o.book_number != null &&
            o.chapter_number != null
        ) {
            return o;
        }
    } catch {
        /* ignore */
    }
    return null;
}

export function registerBiblePublicIndex(Alpine) {
    Alpine.data('biblePublicIndex', () => ({
        last: null,

        init() {
            this.last = parseLast();
        },

        continueHref() {
            const L = this.last;
            const tpl =
                (window.__biblePublicIndexConfig &&
                    window.__biblePublicIndexConfig.chapterUrlTemplate) ||
                '';
            if (!L || !tpl) {
                return '#';
            }
            return tpl
                .split('__V__')
                .join(encodeURIComponent(String(L.versionAbbr)))
                .split('__B__')
                .join(encodeURIComponent(String(L.book_number)))
                .split('__C__')
                .join(encodeURIComponent(String(L.chapter_number)));
        },
    }));
}
