<?php

namespace Modules\Bible\App\Services;

use Illuminate\Console\OutputStyle;
use Modules\Bible\App\Models\BibleVersion;
use Modules\Bible\App\Models\Book;
use Modules\Bible\App\Models\Chapter;
use Modules\Bible\App\Models\Verse;

class BibleBulkJsonImporter
{
    /**
     * Remove todos os livros/capítulos/versículos da versão e reinsere a partir do array JSON decodificado.
     *
     * @param  array<int, array<string, mixed>>  $data
     * @return array{books: int, chapters: int, verses: int}
     */
    public static function purgeAndImport(BibleVersion $version, array $data, ?OutputStyle $output = null): array
    {
        $version->books()->each(function ($book) {
            $book->chapters()->each(function ($chapter) {
                $chapter->verses()->delete();
            });
            $book->chapters()->delete();
        });
        $version->books()->delete();

        $booksCount = 0;
        $chaptersCount = 0;
        $versesCount = 0;
        $bookNumber = 0;
        $now = now();

        foreach ($data as $bookData) {
            $bookNumber++;
            $bookName = $bookData['name'] ?? '';
            $bookAbbrev = $bookData['abbrev'] ?? '';
            $chapters = $bookData['chapters'] ?? [];

            if (empty($bookName) || empty($chapters)) {
                if ($output) {
                    $output->writeln("<comment>Livro sem nome ou capítulos (ordem {$bookNumber}), pulando...</comment>");
                }

                continue;
            }

            $testament = $bookNumber <= 39 ? 'old' : 'new';

            $totalChaptersForBook = count($chapters);
            $totalVersesForBook = 0;
            foreach ($chapters as $chapterVerses) {
                if (is_array($chapterVerses)) {
                    $totalVersesForBook += count($chapterVerses);
                }
            }

            $book = Book::create([
                'bible_version_id' => $version->id,
                'name' => $bookName,
                'book_number' => $bookNumber,
                'abbreviation' => $bookAbbrev,
                'testament' => $testament,
                'order' => $bookNumber,
                'total_chapters' => $totalChaptersForBook,
                'total_verses' => $totalVersesForBook,
            ]);

            $booksCount++;
            if ($output) {
                $output->writeln("  <info>{$bookName}</info> ({$totalChaptersForBook} cap.)");
            }

            $chaptersToInsert = [];
            $versesPayloads = [];

            $chapterNumber = 0;
            foreach ($chapters as $chapterVerses) {
                $chapterNumber++;

                if (! is_array($chapterVerses) || empty($chapterVerses)) {
                    continue;
                }

                $chaptersToInsert[] = [
                    'book_id' => $book->id,
                    'chapter_number' => $chapterNumber,
                    'total_verses' => count($chapterVerses),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $chaptersCount++;

                $versesPayloads[$chapterNumber] = $chapterVerses;
            }

            foreach (array_chunk($chaptersToInsert, 500) as $chunk) {
                Chapter::insert($chunk);
            }

            $chapterMap = Chapter::where('book_id', $book->id)
                ->pluck('id', 'chapter_number');

            $versesToInsert = [];
            foreach ($versesPayloads as $cNum => $versesList) {
                if (! isset($chapterMap[$cNum])) {
                    continue;
                }

                $cId = $chapterMap[$cNum];
                $verseNumber = 0;

                foreach ($versesList as $verseText) {
                    $verseNumber++;

                    if (is_array($verseText)) {
                        $verseText = implode(' ', $verseText);
                    }

                    $verseText = (string) $verseText;

                    if (empty(trim($verseText))) {
                        continue;
                    }

                    $versesToInsert[] = [
                        'chapter_id' => $cId,
                        'verse_number' => $verseNumber,
                        'text' => trim($verseText),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    $versesCount++;
                }
            }

            foreach (array_chunk($versesToInsert, 1000) as $chunk) {
                Verse::insert($chunk);
            }
        }

        return [
            'books' => $booksCount,
            'chapters' => $chaptersCount,
            'verses' => $versesCount,
        ];
    }
}
