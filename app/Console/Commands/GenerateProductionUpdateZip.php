<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class GenerateProductionUpdateZip extends Command
{
    protected $signature = 'update:generate-production-zip';

    protected $description = 'Gera ZIP com ficheiros-chave do painel de líderes JUBAF e PWA para deploy';

    public function handle()
    {
        $this->info('A gerar ZIP de atualização (JUBAF / painel de líderes)...');
        $this->newLine();

        $zipFileName = 'jubaf-lideres-update-' . date('Y-m-d-His') . '.zip';
        $zipPath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error('Não foi possível criar o ficheiro ZIP.');

            return 1;
        }

        $filesToInclude = [
            ['source' => 'routes/lideres.php', 'dest' => 'routes/lideres.php', 'type' => 'file'],
            ['source' => 'routes/web.php', 'dest' => 'routes/web.php', 'type' => 'file'],
            ['source' => 'bootstrap/app.php', 'dest' => 'bootstrap/app.php', 'type' => 'file'],
            ['source' => 'config/jubaf_roles.php', 'dest' => 'config/jubaf_roles.php', 'type' => 'file'],
            ['source' => 'Modules/PainelLider', 'dest' => 'Modules/PainelLider', 'type' => 'dir'],
            ['source' => 'public/sw.js', 'dest' => 'public/sw.js', 'type' => 'file'],
            ['source' => 'public/manifest.json', 'dest' => 'public/manifest.json', 'type' => 'file'],
            ['source' => 'public/offline.html', 'dest' => 'public/offline.html', 'type' => 'file'],
            ['source' => 'public/icons/icon.svg', 'dest' => 'public/icons/icon.svg', 'type' => 'file'],
            ['source' => 'resources/js/offline', 'dest' => 'resources/js/offline', 'type' => 'dir'],
            ['source' => 'resources/js/app.js', 'dest' => 'resources/js/app.js', 'type' => 'file'],
            ['source' => 'vite.config.js', 'dest' => 'vite.config.js', 'type' => 'file'],
            ['source' => 'package.json', 'dest' => 'package.json', 'type' => 'file'],
        ];

        $this->info('A adicionar ficheiros ao ZIP...');
        $addedCount = 0;

        foreach ($filesToInclude as $item) {
            $source = $item['source'];
            $destination = $item['dest'];
            $type = $item['type'];

            if ($type === 'file') {
                $filePath = base_path($source);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $destination);
                    $addedCount++;
                    $this->line("  ✓ {$destination}");
                } else {
                    $this->warn("  ⚠ Ficheiro não encontrado: {$source}");
                }
            } else {
                $dirPath = base_path($source);
                if (is_dir($dirPath)) {
                    $this->addDirectoryToZip($zip, $dirPath, $destination);
                    $addedCount++;
                    $this->line("  ✓ {$destination}/");
                } else {
                    $this->warn("  ⚠ Pasta não encontrada: {$source}");
                }
            }
        }

        $instructions = $this->getInstructions();
        $zip->addFromString('INSTRUCOES_INSTALACAO.txt', $instructions);
        $this->line('  ✓ INSTRUCOES_INSTALACAO.txt');

        if (file_exists(base_path('CHANGELOG.md'))) {
            $changelog = file_get_contents(base_path('CHANGELOG.md'));
            $zip->addFromString('CHANGELOG.md', $changelog);
            $this->line('  ✓ CHANGELOG.md');
        }

        $zip->close();

        $this->newLine();
        $this->info('ZIP gerado com sucesso.');
        $this->info("Localização: {$zipPath}");
        $this->info('Total de entradas principais: ' . ($addedCount + 1));
        $this->newLine();
        $this->comment('Comando: php artisan update:generate-production-zip');

        return 0;
    }

    private function addDirectoryToZip($zip, $dirPath, $zipPath)
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipPath . '/' . substr($filePath, strlen($dirPath) + 1);
                $relativePath = str_replace('\\', '/', $relativePath);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    private function getInstructions(): string
    {
        return <<<'INSTRUCTIONS'
===========================================
INSTRUÇÕES — Pacote JUBAF (painel de líderes)
===========================================

Este pacote contém rotas, módulo PainelLider, PWA (sw.js, manifest) e assets JS
relacionados com o prefixo /lideres.

1) BACKUP
   Base de dados, .env, storage/app/public.

2) EXTRAIR
   Copiar para a raiz do projeto Laravel mantendo a estrutura de pastas.

3) REMOVER public/hot (produção)
   Linux/Mac: rm public/hot
   Windows: del public\hot

4) LIMPAR CACHE LARAVEL
   php artisan optimize:clear

5) DEPENDÊNCIAS E BUILD
   npm install
   npm run build

6) ROTAS
   php artisan route:list --path=lideres

7) SERVICE WORKER
   Após deploy, nos DevTools (Application > Service Workers) pode ser necessário
   fazer unregister e recarregar para aplicar sw.js novo.

8) MIGRAÇÕES / PAPEL "lider"
   Garantir migrações aplicadas e utilizadores com papel Spatie "lider" conforme
   a documentação interna do projeto.

===========================================
INSTRUCTIONS;
    }
}
