<?php

namespace Modules\Bible\App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Bible\App\Models\BibleCommentaryEntry;
use Modules\Bible\App\Models\BibleCommentarySource;

class ImportCommentaryCommand extends Command
{
    protected $signature = 'bible:import-commentary
                            {--path= : Caminho para commentary.json}
                            {--fresh : Remove entradas do source (slug) antes de importar}';

    protected $description = 'Importa fonte e excertos de comentário (JSON)';

    public function handle(): int
    {
        $path = $this->option('path') ?: storage_path('app/private/bible/offline/commentary.sample.json');

        if (! is_file($path)) {
            $this->error("Ficheiro não encontrado: {$path}");

            return 1;
        }

        $raw = json_decode(file_get_contents($path), true);
        if (! is_array($raw)) {
            $this->error('JSON inválido.');

            return 1;
        }

        $src = $raw['source'] ?? null;
        if (! is_array($src) || empty($src['slug'])) {
            $this->error('Falta objeto "source" com slug.');

            return 1;
        }

        $slug = (string) $src['slug'];

        $source = BibleCommentarySource::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'title' => (string) ($src['title'] ?? $slug),
                'language' => mb_substr((string) ($src['language'] ?? 'pt'), 0, 8),
                'license_note' => $src['license_note'] ?? null,
                'url_template' => isset($src['url_template']) ? mb_substr((string) $src['url_template'], 0, 512) : null,
                'is_active' => (bool) ($src['is_active'] ?? true),
            ]
        );

        if ($this->option('fresh')) {
            BibleCommentaryEntry::query()->where('source_id', $source->id)->delete();
            $this->info('Entradas anteriores removidas para este source.');
        }

        $entries = $raw['entries'] ?? [];
        if (! is_array($entries)) {
            $this->error('Falta array "entries".');

            return 1;
        }

        $sort = 0;
        foreach ($entries as $row) {
            if (! is_array($row)) {
                continue;
            }
            $cf = (int) ($row['chapter_from'] ?? $row['chapter'] ?? 0);
            $vf = (int) ($row['verse_from'] ?? $row['verse'] ?? 0);
            $ct = (int) ($row['chapter_to'] ?? $cf);
            $vt = (int) ($row['verse_to'] ?? $vf);

            BibleCommentaryEntry::query()->create([
                'source_id' => $source->id,
                'book_number' => (int) ($row['book_number'] ?? 0),
                'chapter_from' => $cf,
                'verse_from' => $vf,
                'chapter_to' => $ct,
                'verse_to' => $vt,
                'body' => (string) ($row['body'] ?? ''),
                'sort_order' => (int) ($row['sort_order'] ?? $sort++),
                'is_active' => (bool) ($row['is_active'] ?? true),
            ]);
        }

        $this->info('Fonte "'.$slug.'" com '.count($entries).' entradas processadas.');

        return 0;
    }
}
