<?php

namespace Modules\Bible\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;
use Modules\Bible\App\Models\BibleInterlinearToken;
use Modules\Bible\App\Services\BibleApiService;
use Modules\Bible\App\Support\InterlinearCanon;

class ImportInterlinearTokensCommand extends Command
{
    protected $signature = 'bible:import-interlinear
                            {--fresh : Remove todos os tokens antes de importar}
                            {--hebrew= : Caminho para hebrew_tagged.json}
                            {--greek= : Caminho para trparsed.json}
                            {--chunk=1000 : Tamanho do lote de insert}';

    protected $description = 'Importa tokens interlineares (AT: hebrew_tagged, NT: trparsed) para a base';

    public function handle(): int
    {
        $hebrewPath = $this->option('hebrew') ?: storage_path('app/private/bible/offline/hebrew_tagged.json');
        $greekPath = $this->option('greek') ?: storage_path('app/private/bible/offline/GRC-Κοινη/trparsed.json');
        $chunk = max(100, (int) $this->option('chunk'));

        if ($this->option('fresh')) {
            $this->warn('Removendo tokens existentes...');
            DB::table('bible_interlinear_tokens')->truncate();
        }

        if (! is_file($hebrewPath)) {
            $this->error("Hebraico não encontrado: {$hebrewPath}");

            return 1;
        }

        if (! is_file($greekPath)) {
            $this->error("Grego não encontrado: {$greekPath}");

            return 1;
        }

        $this->importHebrew($hebrewPath, $chunk);
        $this->importGreek($greekPath, $chunk);

        $this->info('Importação interlinear concluída.');

        app(BibleApiService::class)->bumpContentRevision();

        return 0;
    }

    private function importHebrew(string $path, int $chunk): void
    {
        $this->info('Importando AT (hebrew_tagged)...');

        $decoder = new ExtJsonDecoder(true);
        $iterator = Items::fromFile($path, ['decoder' => $decoder]);

        $batch = [];
        $bookCount = 0;

        foreach ($iterator as $bookEn => $chapters) {
            if (! is_array($chapters)) {
                continue;
            }

            $bookNumber = InterlinearCanon::bookNumberFromEnglishName((string) $bookEn);
            if ($bookNumber === null || $bookNumber > 39) {
                continue;
            }

            $bookCount++;
            $chapterNum = 0;

            foreach ($chapters as $chapterVerses) {
                $chapterNum++;
                if (! is_array($chapterVerses)) {
                    continue;
                }

                $verseNum = 0;
                foreach ($chapterVerses as $verseTokens) {
                    $verseNum++;
                    if (! is_array($verseTokens)) {
                        continue;
                    }

                    $tokenIndex = 0;
                    foreach ($verseTokens as $segment) {
                        if (! is_array($segment) || count($segment) < 3) {
                            continue;
                        }

                        $tokenIndex++;
                        $rawStrong = (string) $segment[1];
                        $batch[] = [
                            'testament' => 'old',
                            'book_number' => $bookNumber,
                            'chapter_number' => $chapterNum,
                            'verse_number' => $verseNum,
                            'token_index' => $tokenIndex,
                            'surface_text' => (string) $segment[0],
                            'strongs_key' => InterlinearCanon::extractStrongsKey($rawStrong),
                            'strongs_raw' => mb_substr($rawStrong, 0, 96),
                            'morphology' => mb_substr((string) $segment[2], 0, 96),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        if (count($batch) >= $chunk) {
                            BibleInterlinearToken::insert($batch);
                            $batch = [];
                        }
                    }
                }
            }

            $this->line("  Livro: {$bookEn}");
        }

        if ($batch !== []) {
            BibleInterlinearToken::insert($batch);
        }

        $this->info("  Livros AT processados: {$bookCount}");
    }

    private function importGreek(string $path, int $chunk): void
    {
        $this->info('Importando NT (trparsed, streaming)...');

        $decoder = new ExtJsonDecoder(true);
        $iterator = Items::fromFile($path, [
            'pointer' => '/verses',
            'decoder' => $decoder,
        ]);

        $batch = [];
        $n = 0;

        foreach ($iterator as $verse) {
            if (! is_array($verse)) {
                continue;
            }

            $bookName = (string) ($verse['book_name'] ?? '');
            $bookNumber = InterlinearCanon::bookNumberFromEnglishName($bookName);
            if ($bookNumber === null || $bookNumber < 40) {
                continue;
            }

            $chapter = (int) ($verse['chapter'] ?? 0);
            $verseNum = (int) ($verse['verse'] ?? 0);
            $text = (string) ($verse['text'] ?? '');

            if ($chapter < 1 || $verseNum < 1 || $text === '') {
                continue;
            }

            preg_match_all('/(\S+)\s+(G\d+)\s+(\S+)/u', $text, $matches, PREG_SET_ORDER);

            $tokenIndex = 0;
            foreach ($matches as $m) {
                $tokenIndex++;
                $batch[] = [
                    'testament' => 'new',
                    'book_number' => $bookNumber,
                    'chapter_number' => $chapter,
                    'verse_number' => $verseNum,
                    'token_index' => $tokenIndex,
                    'surface_text' => $m[1],
                    'strongs_key' => strtoupper($m[2]),
                    'strongs_raw' => strtoupper($m[2]),
                    'morphology' => mb_substr($m[3], 0, 96),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= $chunk) {
                    BibleInterlinearToken::insert($batch);
                    $batch = [];
                }
            }

            $n++;
            if ($n % 5000 === 0) {
                $this->line("  Versículos NT: {$n}");
            }
        }

        if ($batch !== []) {
            BibleInterlinearToken::insert($batch);
        }

        $this->info("  Versículos NT processados: {$n}");
    }
}
