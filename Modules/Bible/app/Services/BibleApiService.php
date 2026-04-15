<?php

namespace Modules\Bible\App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Bible\App\Models\BibleBookPanorama;
use Modules\Bible\App\Models\BibleVersion;
use Modules\Bible\App\Models\Book;
use Modules\Bible\App\Models\Chapter;
use Modules\Bible\App\Models\Verse;

class BibleApiService
{
    private const CACHE_TTL_SECONDS = 3600;

    private const CACHE_PREFIX = 'bible_api_';

    /** Chave do contador de revisão: ao incrementar, todas as chaves `remember` passam a usar novo sufixo. */
    private const CACHE_REV_KEY = 'bible_api_content_rev';

    /**
     * Invalida caches de listagens (versões, livros, capítulos) após import ou alteração no SuperAdmin.
     */
    public function bumpContentRevision(): void
    {
        Cache::forever(self::CACHE_REV_KEY, (string) hrtime(true));
    }

    private function cacheRevisionSuffix(): string
    {
        return (string) Cache::get(self::CACHE_REV_KEY, '0');
    }

    /**
     * Get active Bible versions (cached).
     *
     * @return Collection<int, BibleVersion>
     */
    public function getVersions(): Collection
    {
        $rev = $this->cacheRevisionSuffix();

        return Cache::remember(self::CACHE_PREFIX.'v'.$rev.'_versions', self::CACHE_TTL_SECONDS, function () {
            return BibleVersion::where('is_active', true)
                ->orderByRaw('is_default DESC')
                ->orderBy('name')
                ->get(['id', 'name', 'abbreviation', 'is_default']);
        });
    }

    /**
     * Get books for a version (cached by version_id).
     *
     * @return Collection<int, Book>
     */
    public function getBooks(?int $versionId = null): Collection
    {
        $versionId = $versionId ?? $this->getDefaultVersionId();
        if (! $versionId) {
            return collect();
        }

        $rev = $this->cacheRevisionSuffix();

        return Cache::remember(self::CACHE_PREFIX."v{$rev}_books_{$versionId}", self::CACHE_TTL_SECONDS, function () use ($versionId) {
            return Book::where('bible_version_id', $versionId)
                ->orderBy('book_number')
                ->get(['id', 'name', 'abbreviation', 'book_number', 'testament']);
        });
    }

    /**
     * Get chapters for a book. Supports book_id or (book_name + version_id).
     *
     * @return Collection<int, Chapter>
     */
    public function getChapters(?int $bookId = null, ?string $bookName = null, ?int $versionId = null): Collection
    {
        if ($bookId) {
            $rev = $this->cacheRevisionSuffix();

            return Cache::remember(self::CACHE_PREFIX."v{$rev}_chapters_{$bookId}", self::CACHE_TTL_SECONDS, function () use ($bookId) {
                return Chapter::where('book_id', $bookId)
                    ->orderBy('chapter_number')
                    ->get(['id', 'chapter_number', 'total_verses']);
            });
        }

        if ($bookName && $versionId) {
            $book = Book::where('bible_version_id', $versionId)
                ->where('name', $bookName)
                ->first();
            if ($book) {
                return $this->getChapters($book->id, null, null);
            }
        }

        return collect();
    }

    /**
     * Get verses for a chapter. Optional verse_range e.g. "1-5" or "1,3,5-10".
     *
     * @return Collection<int, Verse>
     */
    public function getVerses(
        ?int $chapterId = null,
        ?int $bookId = null,
        ?int $chapterNumber = null,
        ?string $verseRange = null
    ): Collection {
        if ($chapterId === null && $bookId && $chapterNumber) {
            $chapter = Chapter::where('book_id', $bookId)
                ->where('chapter_number', $chapterNumber)
                ->first();
            $chapterId = $chapter?->id;
        }

        if (! $chapterId) {
            return collect();
        }

        $query = Verse::where('chapter_id', $chapterId)->orderBy('verse_number');

        if ($verseRange !== null && $verseRange !== '') {
            $ranges = $this->parseVerseRange($verseRange);
            if ($ranges !== []) {
                $query->where(function ($q) use ($ranges) {
                    foreach ($ranges as $range) {
                        if (is_array($range)) {
                            $q->orWhereBetween('verse_number', $range);
                        } else {
                            $q->orWhere('verse_number', $range);
                        }
                    }
                });
            }
        }

        return $query->get(['id', 'verse_number', 'text']);
    }

    /**
     * Find by reference string (e.g. "João 3:16" or "Salmos 23:1-3").
     *
     * @return array{
     *     reference: string,
     *     book: string,
     *     book_number: int,
     *     chapter: int,
     *     verse_start: int,
     *     verse_end: int,
     *     verses: Collection,
     *     full_chapter_url: string,
     *     bible_version_id: int,
     *     version_abbreviation: string
     * }|null
     */
    public function findByReference(string $reference, ?int $bibleVersionId = null): ?array
    {
        $reference = trim($reference);
        if ($reference === '') {
            return null;
        }

        if (! preg_match('/^(.+?)\s+(\d+):(\d+)(?:-(\d+))?$/u', $reference, $matches)) {
            return null;
        }

        $bookName = trim($matches[1]);
        $chapterNum = (int) $matches[2];
        $verseStart = (int) $matches[3];
        $verseEnd = (int) ($matches[4] ?? $verseStart);

        $bibleVersionId = $bibleVersionId ?? $this->getDefaultVersionId()
            ?? BibleVersion::query()->where('is_active', true)->value('id');

        if ($bibleVersionId === null) {
            return null;
        }

        $book = $this->resolveBookByName($bookName, (int) $bibleVersionId);

        if (! $book) {
            return null;
        }

        $chapter = Chapter::where('book_id', $book->id)
            ->where('chapter_number', $chapterNum)
            ->first();

        if (! $chapter) {
            return null;
        }

        $verses = Verse::where('chapter_id', $chapter->id)
            ->whereBetween('verse_number', [$verseStart, $verseEnd])
            ->orderBy('verse_number')
            ->get(['id', 'verse_number', 'text']);

        if ($verses->isEmpty()) {
            return null;
        }

        $refStr = $book->name.' '.$chapter->chapter_number.':'.$verseStart;
        if ($verseEnd !== $verseStart) {
            $refStr .= '-'.$verseEnd;
        }

        $versionAbbr = $book->bibleVersion->abbreviation;
        $fullChapterUrl = \Illuminate\Support\Facades\Route::has('bible.public.chapter')
            ? route('bible.public.chapter', [$versionAbbr, $book->book_number, $chapter->chapter_number])
            : '#';

        return [
            'reference' => $refStr,
            'book' => $book->name,
            'book_number' => $book->book_number,
            'chapter' => $chapter->chapter_number,
            'verse_start' => $verseStart,
            'verse_end' => $verseEnd,
            'verses' => $verses,
            'full_chapter_url' => $fullChapterUrl,
            'bible_version_id' => (int) $book->bible_version_id,
            'version_abbreviation' => $versionAbbr,
        ];
    }

    /**
     * Resolve a book by Portuguese (or imported) name / abbreviation within one version.
     */
    private function resolveBookByName(string $bookName, ?int $bibleVersionId): ?Book
    {
        $q = Book::query()->with('bibleVersion');
        if ($bibleVersionId !== null) {
            $q->where('bible_version_id', $bibleVersionId);
        }

        $exact = (clone $q)->where(function ($inner) use ($bookName): void {
            $inner->where('name', $bookName)->orWhere('abbreviation', $bookName);
        })->first();

        if ($exact) {
            return $exact;
        }

        $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $bookName);
        $like = '%'.$escaped.'%';

        return (clone $q)->where(function ($inner) use ($like): void {
            $inner->where('name', 'like', $like)->orWhere('abbreviation', 'like', $like);
        })->first();
    }

    /**
     * Search verses by text (LIKE).
     *
     * @return Collection<int, Verse>
     */
    public function search(string $query, int $limit = 20, ?string $versionAbbr = null): Collection
    {
        $query = trim($query);
        if ($query === '') {
            return collect();
        }

        $limit = max(1, min(50, $limit));
        $terms = collect(preg_split('/\s+/u', $query) ?: [])
            ->map(static fn ($term) => trim((string) $term))
            ->filter()
            ->values();

        $databaseDriver = DB::connection()->getDriverName();
        $usesMySql = in_array($databaseDriver, ['mysql', 'mariadb'], true);
        $queryBuilder = Verse::query()
            ->with('chapter.book');

        if ($versionAbbr !== null && $versionAbbr !== '') {
            $queryBuilder->whereHas('chapter.book.bibleVersion', function ($builder) use ($versionAbbr): void {
                $builder->where('abbreviation', $versionAbbr);
            });
        }

        if ($usesMySql && $terms->isNotEmpty()) {
            $booleanQuery = $terms
                ->map(static fn ($term) => '+'.preg_replace('/[^\p{L}\p{N}_-]+/u', '', $term))
                ->filter()
                ->implode(' ');

            if ($booleanQuery !== '') {
                return $queryBuilder
                    ->whereFullText('text', $booleanQuery, ['mode' => 'boolean'])
                    ->orderByRaw('MATCH(text) AGAINST (? IN BOOLEAN MODE) DESC', [$booleanQuery])
                    ->take($limit)
                    ->get();
            }
        }

        return $queryBuilder
            ->where(function ($builder) use ($terms, $query): void {
                if ($terms->isEmpty()) {
                    $builder->where('text', 'like', '%'.$query.'%');

                    return;
                }

                foreach ($terms as $term) {
                    $builder->where('text', 'like', '%'.$term.'%');
                }
            })
            ->orderBy('id', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get one random verse. Optional version_id to restrict to a version.
     */
    public function getRandomVerse(?int $versionId = null): ?Verse
    {
        $q = Verse::with('chapter.book')->inRandomOrder();

        if ($versionId) {
            $q->whereHas('chapter.book', fn ($b) => $b->where('bible_version_id', $versionId));
        }

        return $q->first();
    }

    /**
     * Versículo estável para a homepage: mesmo texto para todos no dia civil (timezone da app),
     * ou referência fixa quando $overrideReference está preenchida.
     *
     * @return array{
     *     text: string,
     *     reference: string,
     *     version_name: string,
     *     version_abbreviation: string,
     *     bible_chapter_url: string|null,
     *     mode: 'daily'|'override'
     * }|null
     */
    public function getHomepageDailyVersePayload(
        ?int $versionId,
        string $overrideReference,
        string $salt,
        ?Carbon $date = null
    ): ?array {
        $date = $date ?? Carbon::now();
        $dateKey = $date->format('Y-m-d');
        $versionId = $versionId ?: $this->getDefaultVersionId();
        if ($versionId === null) {
            return null;
        }

        $version = BibleVersion::query()->where('id', $versionId)->where('is_active', true)->first();
        if (! $version) {
            return null;
        }

        $overrideReference = trim($overrideReference);
        if ($overrideReference !== '') {
            $resolved = $this->findByReference($overrideReference, $versionId);
            if ($resolved === null) {
                return null;
            }

            return $this->formatHomepageVersePayloadFromFind($resolved, 'override');
        }

        $rev = $this->cacheRevisionSuffix();
        $cacheKey = self::CACHE_PREFIX.'v'.$rev.'_homepage_daily_'.$dateKey.'_v'.$versionId.'_'.hash('sha256', $salt);

        $ttlSeconds = max(60, (int) $date->diffInSeconds($date->copy()->endOfDay()));

        return Cache::remember($cacheKey, $ttlSeconds, function () use ($versionId, $dateKey, $salt, $version) {
            $verse = $this->pickDeterministicDailyVerse($versionId, $dateKey, $salt);
            if (! $verse) {
                return null;
            }

            $verse->loadMissing('chapter.book.bibleVersion');
            $book = $verse->chapter->book;
            $chapter = $verse->chapter;
            $refStr = $book->name.' '.$chapter->chapter_number.':'.$verse->verse_number;
            $abbr = $book->bibleVersion->abbreviation;
            $chapterUrl = \Illuminate\Support\Facades\Route::has('bible.public.chapter')
                ? route('bible.public.chapter', [$abbr, $book->book_number, $chapter->chapter_number])
                : null;

            return [
                'text' => (string) $verse->text,
                'reference' => $refStr,
                'version_name' => $version->name,
                'version_abbreviation' => $version->abbreviation,
                'bible_chapter_url' => $chapterUrl ? $chapterUrl.'#v-'.$verse->verse_number : $chapterUrl,
                'mode' => 'daily',
            ];
        });
    }

    /**
     * @param  array<string, mixed>  $find
     * @return array{
     *     text: string,
     *     reference: string,
     *     version_name: string,
     *     version_abbreviation: string,
     *     bible_chapter_url: string|null,
     *     mode: 'daily'|'override'
     * }
     */
    private function formatHomepageVersePayloadFromFind(array $find, string $mode): array
    {
        /** @var \Illuminate\Support\Collection<int, Verse> $verses */
        $verses = $find['verses'];
        $text = $verses->pluck('text')->implode(' ');

        $version = BibleVersion::find($find['bible_version_id']);

        return [
            'text' => $text,
            'reference' => $find['reference'],
            'version_name' => $version?->name ?? '',
            'version_abbreviation' => $find['version_abbreviation'],
            'bible_chapter_url' => isset($find['full_chapter_url']) && is_string($find['full_chapter_url']) && $find['full_chapter_url'] !== '#'
                ? rtrim($find['full_chapter_url'], '#').'#v-'.$find['verse_start']
                : ($find['full_chapter_url'] ?? null),
            'mode' => $mode,
        ];
    }

    private function pickDeterministicDailyVerse(int $versionId, string $dateKey, string $salt): ?Verse
    {
        $countCache = self::CACHE_PREFIX.'v'.$this->cacheRevisionSuffix().'_verse_count_v'.$versionId;

        $count = Cache::remember($countCache, self::CACHE_TTL_SECONDS, function () use ($versionId) {
            return Verse::query()
                ->whereHas('chapter.book', fn ($b) => $b->where('bible_version_id', $versionId))
                ->count();
        });

        if ($count < 1) {
            return null;
        }

        $hashInput = $dateKey.'|'.$salt.'|'.$versionId;
        $offset = (int) (hexdec(substr(hash('sha256', $hashInput), 0, 8)) % $count);

        return Verse::query()
            ->with(['chapter.book'])
            ->whereHas('chapter.book', fn ($b) => $b->where('bible_version_id', $versionId))
            ->orderBy('id')
            ->skip($offset)
            ->first();
    }

    /**
     * Compare verse(s) between two versions. v1/v2 can be version id or abbreviation.
     *
     * @param int|string $v1 version id or abbreviation
     * @param int|string $v2 version id or abbreviation
     * @return array{v1: array{abbreviation: string, name: string, verses: Collection}, v2: array{abbreviation: string, name: string, verses: Collection}}|null
     */
    public function compare($v1, $v2, int $bookNumber, int $chapter, ?int $verse = null): ?array
    {
        $version1 = is_int($v1) || ctype_digit((string) $v1)
            ? BibleVersion::find((int) $v1)
            : BibleVersion::where('abbreviation', $v1)->first();
        $version2 = is_int($v2) || ctype_digit((string) $v2)
            ? BibleVersion::find((int) $v2)
            : BibleVersion::where('abbreviation', $v2)->first();

        if (! $version1 || ! $version2) {
            return null;
        }

        $book1 = Book::where('bible_version_id', $version1->id)->where('book_number', $bookNumber)->first();
        $book2 = Book::where('bible_version_id', $version2->id)->where('book_number', $bookNumber)->first();

        if (! $book1 || ! $book2) {
            return null;
        }

        $query1 = Verse::select('verses.*')
            ->join('chapters', 'verses.chapter_id', '=', 'chapters.id')
            ->where('chapters.book_id', $book1->id)
            ->where('chapters.chapter_number', $chapter);

        $query2 = Verse::select('verses.*')
            ->join('chapters', 'verses.chapter_id', '=', 'chapters.id')
            ->where('chapters.book_id', $book2->id)
            ->where('chapters.chapter_number', $chapter);

        if ($verse !== null) {
            $query1->where('verses.verse_number', $verse);
            $query2->where('verses.verse_number', $verse);
        } else {
            $query1->orderBy('verses.verse_number');
            $query2->orderBy('verses.verse_number');
        }

        return [
            'v1' => [
                'abbreviation' => $version1->abbreviation,
                'name' => $version1->name,
                'verses' => $query1->get(),
            ],
            'v2' => [
                'abbreviation' => $version2->abbreviation,
                'name' => $version2->name,
                'verses' => $query2->get(),
            ],
        ];
    }

    /**
     * Get chapter audio URL for a version (if template is configured).
     *
     * @param int|string $version version id or abbreviation
     * @return string|null URL or null if no template
     */
    public function getChapterAudioUrl(int|string $version, int $bookNumber, int $chapterNumber): ?string
    {
        $versionModel = is_int($version) || ctype_digit((string) $version)
            ? BibleVersion::find((int) $version)
            : BibleVersion::where('abbreviation', $version)->first();

        if (! $versionModel) {
            return null;
        }

        return $versionModel->getChapterAudioUrl($bookNumber, $chapterNumber);
    }

    /**
     * Get book panorama (author, date, theme, recipients) by canonical book number.
     *
     * @return array{author: string|null, date_written: string|null, theme_central: string|null, recipients: string|null}|null
     */
    public function getPanoramaByBookNumber(int $bookNumber, ?string $language = 'pt'): ?array
    {
        $bookNumber = max(1, min(66, $bookNumber));
        $language = $language ?: 'pt';

        $panorama = BibleBookPanorama::where('book_number', $bookNumber)
            ->where('language', $language)
            ->first();

        if (! $panorama) {
            return null;
        }

        return [
            'author' => $panorama->author,
            'date_written' => $panorama->date_written,
            'theme_central' => $panorama->theme_central,
            'recipients' => $panorama->recipients,
        ];
    }

    /**
     * @deprecated Use {@see bumpContentRevision()}.
     */
    public function clearCache(): void
    {
        $this->bumpContentRevision();
    }

    private function getDefaultVersionId(): ?int
    {
        $v = BibleVersion::where('is_active', true)
            ->orderByRaw('is_default DESC')
            ->first();

        return $v?->id;
    }

    /**
     * @return array<int|array{0: int, 1: int}>
     */
    private function parseVerseRange(string $verseRange): array
    {
        $ranges = [];
        $parts = explode(',', $verseRange);

        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }
            if (str_contains($part, '-')) {
                $segments = explode('-', $part, 2);
                $start = (int) trim($segments[0]);
                $end = (int) trim($segments[1]);
                if ($start > 0 && $end >= $start) {
                    $ranges[] = [$start, $end];
                }
            } else {
                $num = (int) $part;
                if ($num > 0) {
                    $ranges[] = $num;
                }
            }
        }

        return $ranges;
    }
}
