<?php

namespace Modules\Bible\App\Services;

use Illuminate\Support\Collection;
use Modules\Bible\App\Models\BibleCommentaryEntry;
use Modules\Bible\App\Models\BibleCrossReference;
use Modules\Bible\App\Models\BibleInterlinearToken;
use Modules\Bible\App\Models\BibleStrongsLexicon;
use Modules\Bible\App\Models\BibleVersion;
use Modules\Bible\App\Models\Book;
use Modules\Bible\App\Support\InterlinearCanon;
use Modules\Bible\App\Support\MorphologyExplainer;

/**
 * Estudo interlinear a partir exclusivamente da base de dados:
 * {@see BibleInterlinearToken}, {@see BibleStrongsLexicon}, versículos em {@see Verse}.
 *
 * Importação: `php artisan bible:import-study` (strongs + tokens) e versões via `bible:import-json` / `bible:import-all`.
 */
class InterlinearStudyService
{
    private const SEMANTIC_BRIDGE = [
        'princípio' => ['começo', 'primeiro', 'primícia'],
        'criou' => ['criar', 'fez', 'formou', 'produziu'],
        'expansão' => ['firmamento', 'expanse', 'arco'],
        'firmamento' => ['expansão'],
        'fez' => ['fazê-la', 'realizou', 'preparou', 'fazer'],
        'separação' => ['separar', 'dividir', 'distinguir'],
        'Deus' => ['Elohim', 'Senhor', 'Divino'],
        'terra' => ['chão', 'mundo', 'país', 'região'],
        'céus' => ['céu', 'firmamento', 'altura', 'celestial'],
        'águas' => ['mar', 'rio', 'corrente'],
        'disse' => ['falou', 'respondeu', 'declarou'],
        'haja' => ['seja', 'exista', 'venha'],
        'meio' => ['centro', 'entre'],
        'entre' => ['meio', 'no meio'],
        'luz' => ['claridade', 'brilho'],
        'trevas' => ['escuridão', 'noite'],
        'noite' => ['trevas', 'escuro'],
        'dia' => ['manhã', 'período'],
        'espírito' => ['vento', 'sopro', 'fôlego'],
        'face' => ['superfície', 'presença'],
        'abismo' => ['profundo', 'profundezas'],
        'No' => ['começo', 'primeiro'],
        'Do' => ['origem'],
        'pela' => ['através'],
        'debaixo' => ['abaixo', 'sob', 'fundo'],
        'sobre' => ['cima', 'topo', 'acima'],
        'foi' => ['ser', 'tornar-se', 'acontecer', 'existir'],
        'era' => ['ser', 'existir'],
    ];

    public function getChapterPayload(
        string $bookEnglish,
        int $chapter,
        string $testament,
        ?string $versionAbbreviation = null,
        ?array $compareAbbreviations = null
    ): array {
        $bookEnglish = InterlinearCanon::englishNameFromAny($bookEnglish);
        $bookNumber = InterlinearCanon::bookNumberFromEnglishName($bookEnglish);
        if ($bookNumber === null) {
            return ['error' => 'book_not_resolved', 'message' => __('Livro não reconhecido.'), 'book' => $bookEnglish];
        }

        $tokens = BibleInterlinearToken::query()
            ->forChapter($testament, $bookNumber, $chapter)
            ->get();

        if ($tokens->isEmpty()) {
            return [
                'error' => 'interlinear_not_imported',
                'message' => __(
                    'Ainda não há dados interlineares na base para este capítulo. Importe os tokens com: php artisan bible:import-study --fresh-interlinear'
                ),
                'book' => $bookEnglish,
                'chapter' => $chapter,
                'testament' => $testament,
            ];
        }

        $strongKeys = $tokens->pluck('strongs_key')->filter()->unique()->values()->all();
        $lexicon = BibleStrongsLexicon::query()
            ->whereIn('strong_number', $strongKeys)
            ->get()
            ->keyBy('strong_number');

        $version = $this->resolveVersion($versionAbbreviation);
        $translation = $this->verseTextsForVersion($version, $bookNumber, $chapter);
        $compare = $this->buildCompareMap($compareAbbreviations, $bookNumber, $chapter, $version);

        $bookDisplayPt = $this->bookDisplayNamePt($version, $bookNumber);

        $crossRefGrouped = BibleCrossReference::query()
            ->where('from_book_number', $bookNumber)
            ->where('from_chapter', $chapter)
            ->orderBy('from_verse')
            ->orderBy('weight')
            ->get()
            ->groupBy('from_verse');

        $commentaryEntries = BibleCommentaryEntry::query()
            ->where('book_number', $bookNumber)
            ->where('is_active', true)
            ->whereHas('source', fn ($q) => $q->where('is_active', true))
            ->with('source')
            ->orderBy('sort_order')
            ->get();

        $versesGrouped = $tokens->groupBy('verse_number')->map->values()->sortKeys();

        $enrichedVerses = [];
        $verseStudy = [];
        foreach ($versesGrouped as $verseNum => $verseTokens) {
            $idx = (int) $verseNum - 1;
            $verseTranslation = $translation[$idx] ?? '';

            $segments = $verseTokens->map(function (BibleInterlinearToken $t) use ($lexicon, $verseTranslation, $testament) {
                $def = $t->strongs_key ? $lexicon->get($t->strongs_key) : null;
                $tag = $t->morphology ?? '';

                return [
                    'word' => $t->surface_text,
                    'strong' => $t->strongs_raw ?? $t->strongs_key,
                    'tag' => $tag,
                    'morphology_human_pt' => MorphologyExplainer::humanize($tag, $testament),
                    'xlit' => $def?->xlit ?? '',
                    'pronounce' => $def?->pronounce ?? '',
                    'lemma_pt' => $def?->lemma_br,
                    'semantic_equivalent_pt' => $def?->semantic_equivalent_pt,
                    'meaning_usage_pt' => $def?->meaning_usage_pt,
                    'pt_suggested' => $this->findSuggestedFromLexicon($t->strongs_key, $verseTranslation, $def),
                ];
            })->values()->all();

            $enrichedVerses[] = $segments;

            $vNum = (int) $verseNum;
            $verseStudy[] = [
                'cross_references' => $this->formatCrossReferencesForVerse(
                    $crossRefGrouped->get($vNum, collect())
                ),
                'commentary' => $this->formatCommentaryForVerse($commentaryEntries, $bookNumber, $chapter, $vNum),
            ];
        }

        return [
            'testament' => $testament,
            'book' => $bookEnglish,
            'book_display_pt' => $bookDisplayPt,
            'chapter' => $chapter,
            'verses' => $enrichedVerses,
            'verse_study' => $verseStudy,
            'translation' => $translation,
            'translation_version_abbrev' => $version?->abbreviation,
            'translations_compare' => $compare,
            'source' => 'database',
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getStrongOccurrences(string $number, int $limit = 50): array
    {
        $number = BibleStrongsLexicon::normalizeNumber($number);
        $q = BibleInterlinearToken::query()->where('strongs_key', $number);

        $total = (clone $q)->count();

        $rows = (clone $q)
            ->orderBy('book_number')
            ->orderBy('chapter_number')
            ->orderBy('verse_number')
            ->orderBy('token_index')
            ->limit(max(1, min(200, $limit)))
            ->get(['testament', 'book_number', 'chapter_number', 'verse_number']);

        $sample = $rows->map(function (BibleInterlinearToken $r) {
            $en = InterlinearCanon::englishNameFromBookNumber((int) $r->book_number);

            return [
                'testament' => $r->testament,
                'book_number' => $r->book_number,
                'book_english' => $en,
                'chapter' => $r->chapter_number,
                'verse' => $r->verse_number,
            ];
        })->values()->all();

        return [
            'strong_number' => $number,
            'total' => $total,
            'sample' => $sample,
        ];
    }

    private function bookDisplayNamePt(?BibleVersion $version, int $bookNumber): ?string
    {
        if (! $version) {
            return null;
        }

        $book = Book::query()
            ->where('bible_version_id', $version->id)
            ->where('book_number', $bookNumber)
            ->first();

        return $book?->name;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, BibleCrossReference>  $rows
     * @return list<array<string, mixed>>
     */
    private function formatCrossReferencesForVerse(Collection $rows): array
    {
        return $rows->map(function (BibleCrossReference $r) {
            $toEn = InterlinearCanon::englishNameFromBookNumber((int) $r->to_book_number);

            return [
                'to_book_number' => $r->to_book_number,
                'to_book_english' => $toEn,
                'to_chapter' => $r->to_chapter,
                'to_verse' => $r->to_verse,
                'kind' => $r->kind,
                'note_pt' => $r->note_pt,
                'source_slug' => $r->source_slug,
            ];
        })->values()->all();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, BibleCommentaryEntry>  $entries
     * @return list<array<string, mixed>>
     */
    private function formatCommentaryForVerse(Collection $entries, int $bookNumber, int $chapter, int $verse): array
    {
        return $entries
            ->filter(fn (BibleCommentaryEntry $e) => $e->coversVerse($bookNumber, $chapter, $verse))
            ->map(function (BibleCommentaryEntry $e) {
                return [
                    'source_slug' => $e->source?->slug,
                    'source_title' => $e->source?->title,
                    'body' => $e->body,
                ];
            })
            ->values()
            ->all();
    }

    public function getStrongDefinition(string $number): ?array
    {
        $number = BibleStrongsLexicon::normalizeNumber((string) $number);
        $row = BibleStrongsLexicon::query()->where('strong_number', $number)->first();

        return $row ? $row->toApiArray() : null;
    }

    public function activeVersionsForSelect(): Collection
    {
        return BibleVersion::query()
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get(['id', 'name', 'abbreviation', 'is_default']);
    }

    private function resolveVersion(?string $abbreviation): ?BibleVersion
    {
        if ($abbreviation) {
            $v = BibleVersion::query()
                ->where('is_active', true)
                ->whereRaw('UPPER(abbreviation) = ?', [strtoupper($abbreviation)])
                ->first();
            if ($v) {
                return $v;
            }
        }

        return BibleVersion::query()->where('is_active', true)->where('is_default', true)->first()
            ?? BibleVersion::query()->where('is_active', true)->orderBy('id')->first();
    }

    /**
     * @return list<string>
     */
    private function verseTextsForVersion(?BibleVersion $version, int $bookNumber, int $chapter): array
    {
        if (! $version) {
            return [];
        }

        $book = Book::query()
            ->where('bible_version_id', $version->id)
            ->where('book_number', $bookNumber)
            ->first();

        if (! $book) {
            return [];
        }

        $ch = $book->chapters()->where('chapter_number', $chapter)->first();
        if (! $ch) {
            return [];
        }

        return $ch->verses()->orderBy('verse_number')->pluck('text')->all();
    }

    /**
     * @param  list<string>|null  $abbrevs
     * @return array<string, list<string>>
     */
    private function buildCompareMap(?array $abbrevs, int $bookNumber, int $chapter, ?BibleVersion $primaryVersion = null): array
    {
        if ($abbrevs === null || $abbrevs === []) {
            return [];
        }

        $out = [];
        foreach ($abbrevs as $ab) {
            $ab = trim((string) $ab);
            if ($ab === '') {
                continue;
            }
            $v = $this->resolveVersion($ab);
            if (! $v) {
                continue;
            }
            if ($primaryVersion && strtoupper($v->abbreviation) === strtoupper($primaryVersion->abbreviation)) {
                continue;
            }
            $out[strtoupper($v->abbreviation)] = $this->verseTextsForVersion($v, $bookNumber, $chapter);
        }

        return $out;
    }

    private function findSuggestedFromLexicon(?string $strongNum, string $verseText, ?BibleStrongsLexicon $def): ?string
    {
        if (! $strongNum || $verseText === '' || ! $def) {
            return null;
        }

        return $this->suggestFromDescription($verseText, (string) ($def->description ?? ''));
    }

    private function suggestFromDescription(string $verseText, string $desc): ?string
    {
        if ($desc === '' || $verseText === '') {
            return null;
        }
        $cleanDesc = $desc;
        if (str_contains($cleanDesc, '--')) {
            $parts = explode('--', $cleanDesc);
            $cleanDesc = end($parts);
        }

        $candidates = preg_split('/[,;:\.]/', $cleanDesc);
        $originalWords = array_filter(explode(' ', $verseText));

        $preparedCands = [];
        foreach ($candidates as $cand) {
            $c = trim(mb_strtolower(preg_replace('/\([^\)]+\)/u', '', $cand)));
            $c = trim(preg_replace('/[^\p{L}\s]/u', '', $c));
            if (! empty($c) && mb_strlen($c) < 35) {
                $preparedCands[] = $c;
            }
        }

        $preparedWords = [];
        foreach ($originalWords as $idx => $vw) {
            $raw = preg_replace('/[^\p{L}]/u', '', $vw);
            $clean = mb_strtolower($raw);
            if (! empty($clean)) {
                $preparedWords[] = ['raw' => $raw, 'clean' => $clean, 'original_idx' => $idx];
            }
        }

        foreach ($preparedWords as $pw) {
            foreach ($preparedCands as $pc) {
                if ($pw['clean'] === $pc) {
                    return $pw['raw'];
                }
            }
        }

        foreach ($preparedWords as $pw) {
            if (isset(self::SEMANTIC_BRIDGE[$pw['raw']])) {
                foreach (self::SEMANTIC_BRIDGE[$pw['raw']] as $synonym) {
                    $cleanSyn = mb_strtolower($synonym);
                    foreach ($preparedCands as $pc) {
                        if ($pc === $cleanSyn) {
                            return $pw['raw'];
                        }
                    }
                }
            }
        }

        foreach ($preparedWords as $pw) {
            foreach ($preparedCands as $pc) {
                if (mb_strlen($pw['clean']) >= 4 && mb_strlen($pc) >= 4) {
                    if (mb_stripos($pw['clean'], $pc) !== false || mb_stripos($pc, $pw['clean']) !== false) {
                        return $pw['raw'];
                    }
                }
            }
        }

        return null;
    }
}
