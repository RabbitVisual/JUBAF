<?php

namespace Modules\Bible\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Bible\App\Models\BibleVersion;
use Modules\Bible\App\Services\BibleApiService;
use Modules\Bible\App\Services\BibleBulkJsonImporter;

class ImportAllBiblesCommand extends Command
{
    protected $signature = 'bible:import-all
                            {--default= : Abreviação da versão padrão (ex: ARA)}
                            {--only= : Importar somente esta abreviação (ex: TB)}';

    protected $description = 'Importa todas as versões listadas em storage/app/private/bible/offline/index.json (13 traduções PT). Use --only para uma versão.';

    public function handle(): int
    {
        $indexPath = storage_path('app/private/bible/offline/index.json');

        if (! file_exists($indexPath)) {
            $this->error("Arquivo index.json não encontrado em: {$indexPath}");

            return 1;
        }

        $indexData = json_decode(file_get_contents($indexPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Erro ao decodificar index.json: '.json_last_error_msg());

            return 1;
        }

        if (! isset($indexData['versions']) || empty($indexData['versions'])) {
            $this->error('Nenhuma versão encontrada no index.json');

            return 1;
        }

        $versions = $indexData['versions'];
        $only = $this->option('only');
        if ($only) {
            $onlyUpper = strtoupper((string) $only);
            $versions = array_filter(
                $versions,
                fn ($v) => strtoupper((string) ($v['abbreviation'] ?? '')) === $onlyUpper
            );
            if ($versions === []) {
                $this->error("Nenhuma versão com abreviação \"{$only}\" no index.json.");

                return 1;
            }
            $this->info("Filtrando apenas: {$onlyUpper}");
        }

        $defaultAbbreviation = $this->option('default');
        $totalVersions = count($versions);
        $importedCount = 0;
        $failedCount = 0;

        $this->info("Encontradas {$totalVersions} versões para importar");
        $this->comment('Dica: após as traduções, rode php artisan bible:import-study --fresh-interlinear para léxico e interlinear na base.');
        $this->newLine();

        $bar = $this->output->createProgressBar($totalVersions);
        $bar->setFormat(' %current%/%max% [%bar%] %message%');
        $bar->setMessage('iniciando');
        $bar->start();

        foreach ($versions as $key => $versionInfo) {
            $fileName = $versionInfo['file'] ?? null;
            $name = $versionInfo['name'] ?? '';
            $abbreviation = $versionInfo['abbreviation'] ?? strtoupper($key);

            $bar->setMessage("{$abbreviation}");

            if (! $fileName) {
                $this->newLine();
                $this->warn("Versão '{$name}' não tem arquivo definido, pulando...");
                $failedCount++;
                $bar->advance();

                continue;
            }

            $filePath = storage_path('app/private/bible/offline/'.$fileName);

            if (! file_exists($filePath)) {
                $this->newLine();
                $this->warn("Arquivo não encontrado: {$fileName}, pulando versão '{$name}'...");
                $failedCount++;
                $bar->advance();

                continue;
            }

            $isDefault = ($defaultAbbreviation && strtoupper($abbreviation) === strtoupper($defaultAbbreviation))
                || (! $defaultAbbreviation && $key === array_key_first($indexData['versions']) && ! $only);

            try {
                $exitCode = $this->importBibleVersionBulk($filePath, $name, $abbreviation, $isDefault);
                if ($exitCode === 0) {
                    $importedCount++;
                } else {
                    $failedCount++;
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Erro ao importar '{$name}': ".$e->getMessage());
                $failedCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('✅ Importação concluída!');
        $this->info("   - Versões importadas: {$importedCount}");

        if ($failedCount > 0) {
            $this->warn("   - Versões com erro: {$failedCount}");
        }

        if ($importedCount > 0) {
            app(BibleApiService::class)->bumpContentRevision();
        }

        return 0;
    }

    private function importBibleVersionBulk(string $filePath, string $name, string $abbreviation, bool $isDefault): int
    {
        if (! file_exists($filePath)) {
            $this->error("Arquivo não encontrado: {$filePath}");

            return 1;
        }

        DB::beginTransaction();

        try {
            $jsonContent = file_get_contents($filePath);
            $data = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Erro ao decodificar JSON: '.json_last_error_msg());
            }

            if (! is_array($data) || empty($data)) {
                throw new \Exception('Arquivo JSON inválido ou vazio');
            }

            $version = BibleVersion::updateOrCreate(
                ['abbreviation' => $abbreviation],
                [
                    'name' => $name,
                    'abbreviation' => $abbreviation,
                    'file_name' => basename($filePath),
                    'is_active' => true,
                    'is_default' => $isDefault,
                    'imported_at' => now(),
                ]
            );

            if ($isDefault) {
                BibleVersion::where('id', '!=', $version->id)->update(['is_default' => false]);
            }

            $stats = BibleBulkJsonImporter::purgeAndImport($version, $data);

            $version->update([
                'total_books' => $stats['books'],
                'total_chapters' => $stats['chapters'],
                'total_verses' => $stats['verses'],
            ]);

            DB::commit();

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Erro na importação: '.$e->getMessage());

            return 1;
        }
    }
}
