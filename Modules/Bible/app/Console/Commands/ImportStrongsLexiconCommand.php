<?php

namespace Modules\Bible\App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Bible\App\Services\BibleApiService;
use Illuminate\Support\Facades\DB;
use Modules\Bible\App\Models\BibleStrongsLexicon;

class ImportStrongsLexiconCommand extends Command
{
    protected $signature = 'bible:import-strongs
                            {--path= : Caminho para strongs.json}
                            {--chunk=800 : Tamanho do lote para upsert}';

    protected $description = 'Importa o léxico Strong (strongs.json) para bible_strongs_lexicon';

    public function handle(): int
    {
        $path = $this->option('path') ?: storage_path('app/private/bible/offline/strongs.json');
        $chunkSize = max(50, (int) $this->option('chunk'));

        if (! is_file($path)) {
            $this->error("Arquivo não encontrado: {$path}");

            return 1;
        }

        $this->info('Lendo strongs.json...');
        $raw = json_decode(file_get_contents($path), true);
        if (! is_array($raw)) {
            $this->error('JSON inválido.');

            return 1;
        }

        $items = $raw['itens'] ?? $raw;
        if (! is_array($items)) {
            $this->error('Estrutura inesperada: falta itens[].');

            return 1;
        }

        $locked = BibleStrongsLexicon::query()
            ->where('admin_locked', true)
            ->pluck('strong_number')
            ->flip()
            ->all();

        $descriptionFrozen = BibleStrongsLexicon::query()
            ->where('description_frozen', true)
            ->pluck('strong_number')
            ->flip()
            ->all();

        $total = count($items);
        $this->info("Total de entradas: {$total}");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $batch = [];
        $batchFrozenPt = [];
        $processed = 0;

        foreach ($items as $row) {
            if (! is_array($row)) {
                $bar->advance();

                continue;
            }

            $num = isset($row['number']) ? strtoupper((string) $row['number']) : '';
            if ($num === '' || isset($locked[$num])) {
                $bar->advance();

                continue;
            }

            $now = now();

            if (isset($descriptionFrozen[$num])) {
                $batchFrozenPt[] = [
                    'strong_number' => $num,
                    'lemma_br' => $row['lemma_br'] ?? null,
                    'traduzido_pt' => (bool) ($row['traduzido_pt'] ?? false),
                    'semantic_equivalent_pt' => $row['semantic_equivalent_pt'] ?? null,
                    'meaning_usage_pt' => $row['meaning_usage_pt'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $processed++;
            } else {
                $desc = $row['description'] ?? null;
                $batch[] = [
                    'strong_number' => $num,
                    'lemma' => $row['lemma'] ?? null,
                    'xlit' => $row['xlit'] ?? null,
                    'pronounce' => isset($row['pronounce']) ? mb_substr((string) $row['pronounce'], 0, 255) : null,
                    'description' => $desc,
                    'description_original' => $desc,
                    'lemma_br' => $row['lemma_br'] ?? null,
                    'traduzido_pt' => (bool) ($row['traduzido_pt'] ?? false),
                    'semantic_equivalent_pt' => $row['semantic_equivalent_pt'] ?? null,
                    'meaning_usage_pt' => $row['meaning_usage_pt'] ?? null,
                    'metadata' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $processed++;
            }

            if (count($batch) >= $chunkSize) {
                $this->flushBatch($batch);
                $batch = [];
            }
            if (count($batchFrozenPt) >= $chunkSize) {
                $this->flushFrozenPtBatch($batchFrozenPt);
                $batchFrozenPt = [];
            }

            $bar->advance();
        }

        if ($batch !== []) {
            $this->flushBatch($batch);
        }
        if ($batchFrozenPt !== []) {
            $this->flushFrozenPtBatch($batchFrozenPt);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Concluído. Linhas processadas (não bloqueadas): {$processed}");

        app(BibleApiService::class)->bumpContentRevision();

        return 0;
    }

    /**
     * @param  array<int, array<string, mixed>>  $batch
     */
    private function flushBatch(array $batch): void
    {
        DB::table('bible_strongs_lexicon')->upsert(
            $batch,
            ['strong_number'],
            [
                'lemma', 'xlit', 'pronounce', 'description',
                'lemma_br', 'traduzido_pt', 'semantic_equivalent_pt', 'meaning_usage_pt',
                'metadata', 'updated_at',
            ]
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $batch
     */
    private function flushFrozenPtBatch(array $batch): void
    {
        DB::table('bible_strongs_lexicon')->upsert(
            $batch,
            ['strong_number'],
            ['lemma_br', 'traduzido_pt', 'semantic_equivalent_pt', 'meaning_usage_pt', 'updated_at']
        );
    }
}
