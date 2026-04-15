<?php

namespace Modules\Bible\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Bible\App\Models\BibleVersion;
use Modules\Bible\App\Services\BibleApiService;
use Modules\Bible\App\Services\BibleBulkJsonImporter;

class ImportBibleJsonCommand extends Command
{
    protected $signature = 'bible:import-json {file : Caminho do arquivo JSON} {--name= : Nome da versão} {--abbreviation= : Abreviação} {--default : Definir como versão padrão}';

    protected $description = 'Importa uma versão da Bíblia a partir de um arquivo JSON';

    public function handle(): int
    {
        $filePath = $this->argument('file');
        $name = $this->option('name') ?: $this->ask('Nome da versão da Bíblia');
        $abbreviation = $this->option('abbreviation') ?: $this->ask('Abreviação (ex: ARA, ARC)');
        $isDefault = $this->option('default');

        if (! file_exists($filePath)) {
            $this->error("Arquivo não encontrado: {$filePath}");

            return 1;
        }

        $this->info("Iniciando importação de: {$name}");

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

            $this->info('Limpando dados antigos e inserindo em lote...');
            $stats = BibleBulkJsonImporter::purgeAndImport($version, $data, $this->output);

            $version->update([
                'total_books' => $stats['books'],
                'total_chapters' => $stats['chapters'],
                'total_verses' => $stats['verses'],
            ]);

            DB::commit();

            app(BibleApiService::class)->bumpContentRevision();

            $this->info('✅ Importação concluída com sucesso!');
            $this->info("   - Livros: {$stats['books']}");
            $this->info("   - Capítulos: {$stats['chapters']}");
            $this->info("   - Versículos: {$stats['verses']}");

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Erro na importação: '.$e->getMessage());
            $this->error($e->getTraceAsString());

            return 1;
        }
    }
}
