<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\JubafSector;

class JubafBackfillSectorsCommand extends Command
{
    protected $signature = 'jubaf:backfill-sectors
                            {--dry-run : Apenas mostrar alterações sem gravar}';

    protected $description = 'Preenche igrejas.jubaf_sector_id com base no texto legado sector (nome do setor)';

    public function handle(): int
    {
        if (! Schema::hasTable('jubaf_sectors') || ! Schema::hasTable('igrejas_churches')) {
            $this->error('Tabelas jubaf_sectors ou igrejas_churches inexistentes.');

            return 1;
        }

        $sectors = JubafSector::query()->get(['id', 'name', 'slug']);
        if ($sectors->isEmpty()) {
            $this->warn('Sem registos em jubaf_sectors. Crie setores antes (seed ou admin).');

            return 0;
        }

        $nameMap = [];
        foreach ($sectors as $s) {
            $key = mb_strtolower(trim($s->name));
            $nameMap[$key] = $s->id;
            $nameMap[mb_strtolower(trim(str_replace('-', ' ', (string) $s->slug)))] = $s->id;
        }

        $dry = (bool) $this->option('dry-run');
        $updated = 0;

        Church::query()
            ->whereNull('jubaf_sector_id')
            ->whereNotNull('sector')
            ->orderBy('id')
            ->chunkById(100, function ($churches) use ($nameMap, $dry, &$updated): void {
                foreach ($churches as $church) {
                    $raw = trim((string) $church->sector);
                    if ($raw === '') {
                        continue;
                    }
                    $key = mb_strtolower($raw);
                    $sectorId = $nameMap[$key] ?? null;
                    if ($sectorId === null) {
                        $compact = preg_replace('/\s+/', ' ', $key) ?? $key;
                        $sectorId = $nameMap[$compact] ?? null;
                    }
                    if ($sectorId === null) {
                        $this->line("  [ignorado] igreja #{$church->id} sector texto não corresponde: {$raw}");

                        continue;
                    }
                    $this->line(($dry ? '[dry-run] ' : '')."igreja #{$church->id} ({$church->name}) → jubaf_sector_id={$sectorId}");
                    if (! $dry) {
                        $church->jubaf_sector_id = $sectorId;
                        $church->saveQuietly();
                    }
                    $updated++;
                }
            });

        $this->info($dry ? "Dry-run: {$updated} igrejas seriam actualizadas." : "Actualizadas {$updated} igrejas.");

        $this->newLine();
        $this->warn('Vice-presidentes: atribua users.jubaf_sector_id em Admin → Utilizadores (não automatizado aqui por segurança).');

        return 0;
    }
}
