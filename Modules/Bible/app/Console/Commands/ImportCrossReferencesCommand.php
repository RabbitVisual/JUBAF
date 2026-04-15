<?php

namespace Modules\Bible\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Bible\App\Models\BibleCrossReference;

class ImportCrossReferencesCommand extends Command
{
    protected $signature = 'bible:import-cross-refs
                            {--path= : Caminho para cross-refs.json}
                            {--fresh : Remove referências existentes com mesmo source_slug antes de importar}';

    protected $description = 'Importa referências cruzadas (JSON) para bible_cross_references';

    public function handle(): int
    {
        $path = $this->option('path') ?: storage_path('app/private/bible/offline/cross-refs.sample.json');

        if (! is_file($path)) {
            $this->error("Ficheiro não encontrado: {$path}");

            return 1;
        }

        $raw = json_decode(file_get_contents($path), true);
        if (! is_array($raw)) {
            $this->error('JSON inválido.');

            return 1;
        }

        $items = $raw['references'] ?? $raw;
        if (! is_array($items)) {
            $this->error('Estrutura inesperada: falta array "references" ou raiz array.');

            return 1;
        }

        $sourceSlug = isset($raw['source_slug']) ? (string) $raw['source_slug'] : null;
        if ($this->option('fresh') && $sourceSlug) {
            BibleCrossReference::query()->where('source_slug', $sourceSlug)->delete();
            $this->info("Removidas entradas com source_slug={$sourceSlug}");
        }

        $now = now();
        $rows = [];
        foreach ($items as $row) {
            if (! is_array($row)) {
                continue;
            }
            $rows[] = [
                'testament' => $row['testament'] ?? 'old',
                'from_book_number' => (int) ($row['from_book'] ?? $row['from_book_number'] ?? 0),
                'from_chapter' => (int) ($row['from_chapter'] ?? 0),
                'from_verse' => (int) ($row['from_verse'] ?? 0),
                'to_book_number' => (int) ($row['to_book'] ?? $row['to_book_number'] ?? 0),
                'to_chapter' => (int) ($row['to_chapter'] ?? 0),
                'to_verse' => (int) ($row['to_verse'] ?? 0),
                'kind' => isset($row['kind']) ? mb_substr((string) $row['kind'], 0, 32) : null,
                'weight' => (int) ($row['weight'] ?? 0),
                'source_slug' => $row['source_slug'] ?? $sourceSlug,
                'note_pt' => isset($row['note_pt']) ? mb_substr((string) $row['note_pt'], 0, 512) : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($rows === []) {
            $this->warn('Nenhuma referência para importar.');

            return 0;
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('bible_cross_references')->insert($chunk);
        }

        $this->info('Importadas '.count($rows).' referências cruzadas.');

        return 0;
    }
}
