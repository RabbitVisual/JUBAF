<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ZipArchive;
use Exception;

class UpdateService
{
    protected $updatesPath;
    protected $backupsPath;
    protected $extractPath;

    public function __construct()
    {
        $this->updatesPath = storage_path('app/updates');
        $this->backupsPath = storage_path('app/backups/updates');
        $this->extractPath = storage_path('app/updates/extracted');

        // Criar diretórios se não existirem
        $this->ensureDirectoriesExist();
    }

    /**
     * Garantir que os diretórios existam
     */
    protected function ensureDirectoriesExist()
    {
        $directories = [
            $this->updatesPath,
            $this->backupsPath,
            $this->extractPath,
        ];

        foreach ($directories as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
        }
    }

    /**
     * Upload de arquivo de atualização
     */
    public function uploadUpdateFile($file, array $options = [])
    {
        $updateId = Str::uuid()->toString();
        $fileName = $updateId . '_' . time() . '.zip';
        $filePath = $this->updatesPath . '/' . $fileName;

        // Obter informações do arquivo ANTES de mover
        $originalName = $file->getClientOriginalName();
        $fileSize = $file->getSize();

        // Validar tamanho do arquivo
        if ($fileSize === false || $fileSize <= 0) {
            throw new Exception('Não foi possível determinar o tamanho do arquivo.');
        }

        // Garantir que o diretório existe e tem permissão de escrita
        if (!File::exists($this->updatesPath)) {
            File::makeDirectory($this->updatesPath, 0755, true);
        }

        if (!is_writable($this->updatesPath)) {
            throw new Exception('O diretório de atualizações não tem permissão de escrita: ' . $this->updatesPath);
        }

        // Definir caminho completo do arquivo
        $filePath = $this->updatesPath . DIRECTORY_SEPARATOR . $fileName;

        // Mover arquivo usando método mais confiável
        try {
            // Usar move() que é mais direto e confiável
            $moved = $file->move($this->updatesPath, $fileName);

            if (!$moved) {
                throw new Exception('Falha ao mover o arquivo para o diretório de destino.');
            }

            // Verificar se o arquivo existe no destino
            if (!File::exists($filePath)) {
                throw new Exception('Arquivo não encontrado após mover para: ' . $filePath);
            }

        } catch (\Exception $e) {
            Log::error('Erro ao salvar arquivo de atualização', [
                'error' => $e->getMessage(),
                'path' => $this->updatesPath,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'trace' => $e->getTraceAsString(),
            ]);
            throw new Exception('Erro ao salvar o arquivo: ' . $e->getMessage());
        }

        // Validação básica: apenas verificar assinatura ZIP (sem abrir ZipArchive)
        // A validação completa será feita apenas quando aplicar a atualização
        try {
            // Verificar se o arquivo tem a assinatura ZIP (primeiros 4 bytes)
            $handle = @fopen($filePath, 'rb');
            if (!$handle) {
                if (File::exists($filePath)) {
                    @unlink($filePath);
                }
                throw new Exception('Não foi possível abrir o arquivo para validação.');
            }

            $header = @fread($handle, 4);
            @fclose($handle);

            // Verificar assinatura ZIP (PK\x03\x04 ou PK\x05\x06 para ZIP vazio)
            if ($header === false || strlen($header) < 2 || substr($header, 0, 2) !== 'PK') {
                if (File::exists($filePath)) {
                    @unlink($filePath);
                }
                throw new Exception('O arquivo não é um ZIP válido. Assinatura inválida.');
            }

            // Validação básica concluída - não vamos abrir ZipArchive aqui
            // A validação completa será feita apenas quando aplicar a atualização
            Log::info('Arquivo ZIP validado (assinatura OK)', [
                'file' => $fileName,
                'size' => $fileSize,
            ]);

        } catch (\Exception $e) {
            // Se for erro de validação, relançar
            if (strpos($e->getMessage(), 'ZIP') !== false ||
                strpos($e->getMessage(), 'arquivo') !== false ||
                strpos($e->getMessage(), 'assinatura') !== false) {
                throw $e;
            }

            // Outros erros: logar e continuar (não bloquear upload)
            Log::warning('Erro na validação inicial do ZIP, continuando mesmo assim', [
                'error' => $e->getMessage(),
                'file' => $fileName,
            ]);
        }

        // Obter tamanho real do arquivo salvo
        $actualSize = File::size($filePath);

        // Criar registro de atualização
        $update = [
            'id' => $updateId,
            'file_name' => $fileName,
            'original_name' => $originalName,
            'size' => $actualSize > 0 ? $actualSize : $fileSize,
            'status' => 'uploaded',
            'created_at' => now(),
            'created_by' => auth()->user()?->id,
            'options' => $options,
        ];

        $this->saveUpdateRecord($update);

        // Salvar opções escolhidas pelo usuário
        Log::info('Arquivo de atualização enviado', [
            'update_id' => $updateId,
            'file_name' => $fileName,
            'user_id' => auth()->user()?->id,
            'options' => $options,
        ]);

        return [
            'update_id' => $updateId,
            'file_name' => $fileName,
        ];
    }

    /**
     * Aplicar atualização
     */
    public function applyUpdate($updateId)
    {
        $update = $this->getUpdate($updateId);

        if (!$update) {
            return ['success' => false, 'message' => 'Atualização não encontrada.'];
        }

        if ($update['status'] === 'applied') {
            return ['success' => false, 'message' => 'Atualização já foi aplicada.'];
        }

        try {
            DB::beginTransaction();

            // Criar backup ANTES de aplicar (respeitando a opção do usuário)
            $updateData = $this->getUpdate($updateId);

            // Verificar opção do usuário - se não existir, assumir true (padrão seguro)
            $userOptions = $updateData['options'] ?? [];
            $shouldCreateBackup = isset($userOptions['create_backup'])
                ? (bool)$userOptions['create_backup']
                : true; // Default true para segurança

            Log::info('Verificando opção de backup antes de aplicar', [
                'update_id' => $updateId,
                'should_create_backup' => $shouldCreateBackup,
                'user_options' => $userOptions,
            ]);

            if ($shouldCreateBackup) {
                if (!isset($updateData['backup_file']) || empty($updateData['backup_file'])) {
                    try {
                        Log::info('Criando backup antes de aplicar atualização (opção do usuário)', [
                            'update_id' => $updateId,
                            'user_requested_backup' => true,
                        ]);
                        $this->createSystemBackup($updateId);
                        Log::info('Backup criado com sucesso antes de aplicar', ['update_id' => $updateId]);
                    } catch (\Exception $e) {
                        Log::error('Erro ao criar backup antes de aplicar', [
                            'update_id' => $updateId,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);
                        // Continuar mesmo sem backup - não bloquear aplicação
                        // Mas registrar que não há backup disponível
                        $this->updateStatus($updateId, 'applying', [
                            'backup_created' => false,
                            'backup_error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    Log::info('Backup já existe para esta atualização', [
                        'update_id' => $updateId,
                        'backup_file' => $updateData['backup_file'],
                    ]);
                }
            } else {
                Log::info('Backup não será criado (opção do usuário desmarcada)', [
                    'update_id' => $updateId,
                    'user_requested_backup' => false,
                ]);
            }

            // Atualizar status
            $this->updateStatus($updateId, 'applying');

            // Extrair arquivo ZIP
            $extractedPath = $this->extractUpdate($updateId);

            // Validar estrutura
            $this->validateUpdateStructure($extractedPath);

            // Aplicar arquivos
            $filesApplied = $this->applyFiles($extractedPath);

            // Executar scripts de atualização se existirem
            $this->runUpdateScripts($extractedPath);

            // Limpar arquivos extraídos
            File::deleteDirectory($extractedPath);

            // Atualizar status
            $this->updateStatus($updateId, 'applied', [
                'files_applied' => $filesApplied,
                'applied_at' => now(),
                'applied_by' => auth()->user()?->id,
            ]);

            DB::commit();

            Log::info('Atualização aplicada com sucesso', [
                'update_id' => $updateId,
                'files_applied' => count($filesApplied),
                'user_id' => auth()->user()?->id,
            ]);

            return [
                'success' => true,
                'message' => 'Atualização aplicada com sucesso!',
                'files_applied' => count($filesApplied),
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $this->updateStatus($updateId, 'failed', [
                'error' => $e->getMessage(),
                'failed_at' => now(),
            ]);

            Log::error('Erro ao aplicar atualização', [
                'update_id' => $updateId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao aplicar atualização: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Extrair arquivo ZIP
     */
    protected function extractUpdate($updateId)
    {
        $update = $this->getUpdate($updateId);
        $filePath = $this->updatesPath . '/' . $update['file_name'];
        $extractPath = $this->extractPath . '/' . $updateId;

        if (File::exists($extractPath)) {
            File::deleteDirectory($extractPath);
        }

        File::makeDirectory($extractPath, 0755, true);

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== TRUE) {
            throw new Exception('Não foi possível abrir o arquivo ZIP.');
        }

        $zip->extractTo($extractPath);
        $zip->close();

        return $extractPath;
    }

    /**
     * Validar estrutura da atualização
     */
    protected function validateUpdateStructure($extractPath)
    {
        // Arquivos realmente perigosos (sempre bloquear)
        $dangerousFiles = ['.env'];

        // Arquivos permitidos na raiz (package.json e vite.config.js são necessários para atualizações frontend)
        $allowedRootFiles = ['package.json', 'vite.config.js', 'composer.json'];

        // Caminhos perigosos (sempre bloquear)
        $dangerousPaths = ['vendor', 'node_modules', 'storage', 'bootstrap/cache', '.git'];

        $files = File::allFiles($extractPath);

        foreach ($files as $file) {
            $relativePath = str_replace($extractPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath); // Normalizar separadores
            $fileName = basename($relativePath);

            // Verificar se é arquivo na raiz (sem subdiretórios)
            $isRootFile = (strpos($relativePath, '/') === false);

            // Verificar arquivos realmente perigosos (sempre bloquear)
            if (in_array($fileName, $dangerousFiles)) {
                throw new Exception("Arquivo não permitido na atualização: {$relativePath}");
            }

            // Permitir arquivos específicos apenas na raiz
            if (in_array($fileName, $allowedRootFiles)) {
                if (!$isRootFile) {
                    throw new Exception("Arquivo permitido apenas na raiz do projeto: {$relativePath}");
                }
                // Se está na raiz, permitir e continuar
                continue;
            }

            // Verificar caminhos perigosos
            foreach ($dangerousPaths as $dangerousPath) {
                if (strpos($relativePath, $dangerousPath . '/') === 0 || $relativePath === $dangerousPath) {
                    throw new Exception("Caminho não permitido na atualização: {$relativePath}");
                }
            }
        }
    }

    /**
     * Aplicar arquivos da atualização
     */
    protected function applyFiles($extractPath)
    {
        $filesApplied = [];
        $basePath = base_path();

        $files = File::allFiles($extractPath);

        foreach ($files as $file) {
            $relativePath = str_replace($extractPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath); // Normalizar para barras normais
            $targetPath = $basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

            // Criar diretório se não existir
            $targetDir = dirname($targetPath);
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            // Fazer backup do arquivo original se existir
            if (File::exists($targetPath)) {
                $backupPath = $this->backupsPath . '/' . md5($relativePath) . '_' . basename($targetPath);
                File::copy($targetPath, $backupPath);
            }

            // Copiar arquivo
            File::copy($file->getPathname(), $targetPath);

            $filesApplied[] = $relativePath;
        }

        return $filesApplied;
    }

    /**
     * Executar scripts de atualização
     */
    protected function runUpdateScripts($extractPath)
    {
        $scriptsPath = $extractPath . '/update-scripts';

        if (!File::exists($scriptsPath)) {
            return;
        }

        // Ler arquivo de instruções se existir
        $instructionsFile = $extractPath . '/INSTRUCOES_INSTALACAO.txt';
        if (File::exists($instructionsFile)) {
            Log::info('Instruções de atualização encontradas', [
                'file' => $instructionsFile,
            ]);
        }

        // Executar comandos Artisan se especificados
        $artisanCommands = [
            'php artisan config:clear',
            'php artisan route:clear',
            'php artisan view:clear',
            'php artisan cache:clear',
            'php artisan optimize:clear',
        ];

        foreach ($artisanCommands as $command) {
            try {
                exec($command . ' 2>&1', $output, $returnCode);
                if ($returnCode !== 0) {
                    Log::warning('Comando Artisan falhou', [
                        'command' => $command,
                        'output' => $output,
                    ]);
                }
            } catch (Exception $e) {
                Log::warning('Erro ao executar comando Artisan', [
                    'command' => $command,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Criar backup do sistema
     */
    protected function createSystemBackup($updateId)
    {
        $backupFileName = 'backup_' . $updateId . '_' . date('Y-m-d_His') . '.zip';
        $backupPath = $this->backupsPath . DIRECTORY_SEPARATOR . $backupFileName;

        // Garantir que o diretório de backups existe
        if (!File::exists($this->backupsPath)) {
            File::makeDirectory($this->backupsPath, 0755, true);
        }

        $zip = new ZipArchive();
        $zipResult = $zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($zipResult !== TRUE) {
            $errorMessages = [
                ZipArchive::ER_EXISTS => 'Arquivo de backup já existe',
                ZipArchive::ER_OPEN => 'Não foi possível abrir/criar arquivo de backup',
                ZipArchive::ER_MEMORY => 'Falha de memória ao criar backup',
            ];
            $errorMsg = $errorMessages[$zipResult] ?? 'Erro desconhecido (código: ' . $zipResult . ')';
            throw new Exception('Não foi possível criar o backup: ' . $errorMsg);
        }

        try {
            // Adicionar apenas arquivos que serão modificados pela atualização
            // Fazer backup seletivo baseado nos arquivos que serão atualizados
            $update = $this->getUpdate($updateId);
            $updateFilePath = $this->updatesPath . DIRECTORY_SEPARATOR . $update['file_name'];

            // Extrair temporariamente para ver quais arquivos serão modificados
            $tempExtractPath = $this->extractPath . DIRECTORY_SEPARATOR . 'temp_' . $updateId;
            if (File::exists($tempExtractPath)) {
                File::deleteDirectory($tempExtractPath);
            }
            File::makeDirectory($tempExtractPath, 0755, true);

            $tempZip = new ZipArchive();
            if ($tempZip->open($updateFilePath) === TRUE) {
                $tempZip->extractTo($tempExtractPath);
                $tempZip->close();

                // Fazer backup apenas dos arquivos que serão modificados
                $files = File::allFiles($tempExtractPath);
                foreach ($files as $file) {
                    $relativePath = str_replace($tempExtractPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $relativePath = str_replace('\\', '/', $relativePath);
                    $targetPath = base_path($relativePath);

                    // Fazer backup apenas se o arquivo existir no projeto
                    if (File::exists($targetPath)) {
                        $zip->addFile($targetPath, $relativePath);
                    }
                }

                // Limpar extração temporária
                File::deleteDirectory($tempExtractPath);
            }

            $zip->close();

            // Verificar se o backup foi criado com sucesso
            if (!File::exists($backupPath)) {
                throw new Exception('Backup criado mas arquivo não encontrado após criação.');
            }

            $this->updateBackupPath($updateId, $backupFileName);

            Log::info('Backup do sistema criado', [
                'update_id' => $updateId,
                'backup_file' => $backupFileName,
                'backup_path' => $backupPath,
                'backup_size' => File::size($backupPath),
            ]);

        } catch (\Exception $e) {
            // Fechar ZIP em caso de erro
            if (isset($zip)) {
                @$zip->close();
            }
            // Deletar backup parcial se existir
            if (File::exists($backupPath)) {
                @unlink($backupPath);
            }
            throw $e;
        }
    }

    /**
     * Adicionar diretório ao ZIP
     */
    protected function addDirectoryToZip($zip, $dir, $basePath)
    {
        $files = File::allFiles($dir);

        foreach ($files as $file) {
            $relativePath = str_replace(base_path() . '/', '', $file->getPathname());
            $zip->addFile($file->getPathname(), $relativePath);
        }
    }

    /**
     * Fazer rollback de atualização
     */
    public function rollbackUpdate($updateId)
    {
        $update = $this->getUpdate($updateId);

        if (!$update || $update['status'] !== 'applied') {
            return ['success' => false, 'message' => 'Não é possível fazer rollback desta atualização. Apenas atualizações aplicadas podem ser revertidas.'];
        }

        try {
            $backupPath = $this->getBackupPath($updateId);

            // Verificar se o backup foi registrado
            if (!$backupPath) {
                $backupFileName = $update['backup_file'] ?? null;
                if (!$backupFileName) {
                    return [
                        'success' => false,
                        'message' => 'Backup não foi criado para esta atualização. O rollback não é possível sem backup. Isso pode ter acontecido se o backup falhou silenciosamente durante a aplicação.'
                    ];
                }
                // Tentar construir o caminho mesmo sem método
                $backupPath = $this->backupsPath . DIRECTORY_SEPARATOR . $backupFileName;
            }

            // Verificar se o arquivo existe
            if (!File::exists($backupPath)) {
                // Listar backups disponíveis para debug
                $availableBackups = [];
                if (File::exists($this->backupsPath)) {
                    $backupFiles = File::files($this->backupsPath);
                    foreach ($backupFiles as $file) {
                        if (strpos($file->getFilename(), $updateId) !== false) {
                            $availableBackups[] = $file->getFilename();
                        }
                    }
                }

                $message = 'Backup não encontrado no caminho esperado: ' . $backupPath;
                if (!empty($availableBackups)) {
                    $message .= '. Backups encontrados com ID similar: ' . implode(', ', $availableBackups);
                }

                Log::error('Backup não encontrado para rollback', [
                    'update_id' => $updateId,
                    'expected_path' => $backupPath,
                    'backup_file' => $update['backup_file'] ?? 'não definido',
                    'available_backups' => $availableBackups,
                ]);

                return ['success' => false, 'message' => $message];
            }

            // Restaurar arquivos do backup
            $zip = new ZipArchive();
            if ($zip->open($backupPath) !== TRUE) {
                throw new Exception('Não foi possível abrir o backup.');
            }

            $zip->extractTo(base_path());
            $zip->close();

            // Limpar TODOS os arquivos individuais (backups antigos) do diretório de backups
            // Esses arquivos são backups antigos que não são mais necessários
            $this->cleanupIndividualBackupFiles(null);

            $this->updateStatus($updateId, 'rolled_back', [
                'rolled_back_at' => now(),
                'rolled_back_by' => auth()->user()?->id,
            ]);

            Log::info('Rollback realizado com sucesso', [
                'update_id' => $updateId,
                'user_id' => auth()->user()?->id,
            ]);

            return ['success' => true, 'message' => 'Rollback realizado com sucesso! Arquivos de backup individuais foram limpos.'];
        } catch (Exception $e) {
            Log::error('Erro ao fazer rollback', [
                'update_id' => $updateId,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'Erro ao fazer rollback: ' . $e->getMessage()];
        }
    }

    /**
     * Obter histórico de atualizações
     */
    public function getUpdateHistory()
    {
        $updatesFile = storage_path('app/updates/updates.json');

        if (!File::exists($updatesFile)) {
            return [];
        }

        $content = File::get($updatesFile);
        $updates = json_decode($content, true) ?? [];

        return collect($updates)->sortByDesc('created_at')->values()->all();
    }

    /**
     * Obter atualização específica
     */
    public function getUpdate($updateId)
    {
        $updates = $this->getUpdateHistory();

        return collect($updates)->firstWhere('id', $updateId);
    }

    /**
     * Obter última atualização
     */
    public function getLastUpdate()
    {
        $updates = $this->getUpdateHistory();

        return collect($updates)->first();
    }

    /**
     * Obter arquivos de uma atualização
     */
    public function getUpdateFiles($updateId)
    {
        $update = $this->getUpdate($updateId);

        if (!$update || !isset($update['files_applied'])) {
            return [];
        }

        return $update['files_applied'] ?? [];
    }

    /**
     * Obter backup de uma atualização
     */
    public function getUpdateBackup($updateId)
    {
        $update = $this->getUpdate($updateId);

        if (!$update || !isset($update['backup_file'])) {
            return null;
        }

        return [
            'file_name' => $update['backup_file'],
            'path' => $this->backupsPath . '/' . $update['backup_file'],
            'exists' => File::exists($this->backupsPath . '/' . $update['backup_file']),
        ];
    }

    /**
     * Obter caminho do backup
     */
    public function getBackupPath($updateId)
    {
        $update = $this->getUpdate($updateId);

        if (!$update || !isset($update['backup_file'])) {
            return null;
        }

        return $this->backupsPath . '/' . $update['backup_file'];
    }

    /**
     * Obter informações do sistema
     */
    public function getSystemInfo()
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'disk_space' => $this->getDiskSpace(),
            'last_update' => $this->getLastUpdate(),
        ];
    }

    /**
     * Obter espaço em disco
     */
    protected function getDiskSpace()
    {
        $total = disk_total_space(base_path());
        $free = disk_free_space(base_path());
        $used = $total - $free;

        return [
            'total' => $this->formatBytes($total),
            'used' => $this->formatBytes($used),
            'free' => $this->formatBytes($free),
            'percent_used' => round(($used / $total) * 100, 2),
        ];
    }

    /**
     * Formatar bytes
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Salvar registro de atualização
     */
    protected function saveUpdateRecord($update)
    {
        $updatesFile = storage_path('app/updates/updates.json');
        $updates = [];

        if (File::exists($updatesFile)) {
            $content = File::get($updatesFile);
            $updates = json_decode($content, true) ?? [];
        }

        $updates[] = $update;

        File::put($updatesFile, json_encode($updates, JSON_PRETTY_PRINT));
    }

    /**
     * Atualizar status de atualização
     */
    protected function updateStatus($updateId, $status, array $additionalData = [])
    {
        $updatesFile = storage_path('app/updates/updates.json');
        $updates = $this->getUpdateHistory();

        foreach ($updates as &$update) {
            if ($update['id'] === $updateId) {
                $update['status'] = $status;
                $update = array_merge($update, $additionalData);
                break;
            }
        }

        File::put($updatesFile, json_encode($updates, JSON_PRETTY_PRINT));
    }

    /**
     * Atualizar caminho do backup
     */
    protected function updateBackupPath($updateId, $backupFileName)
    {
        // Atualizar apenas o backup_file sem alterar o status
        $updatesFile = storage_path('app/updates/updates.json');
        $updates = $this->getUpdateHistory();

        foreach ($updates as &$update) {
            if ($update['id'] === $updateId) {
                $update['backup_file'] = $backupFileName;
                break;
            }
        }

        File::put($updatesFile, json_encode($updates, JSON_PRETTY_PRINT));
    }

    /**
     * Limpar arquivos individuais de backup (backups antigos com hash MD5)
     * Mantém apenas os arquivos ZIP de backup válidos
     *
     * @param string|null $updateId Se fornecido, limpa apenas arquivos relacionados. Se null, limpa todos os arquivos individuais.
     */
    protected function cleanupIndividualBackupFiles($updateId = null)
    {
        try {
            if (!File::exists($this->backupsPath)) {
                return;
            }

            $files = File::files($this->backupsPath);
            $deletedCount = 0;
            $deletedFiles = [];

            foreach ($files as $file) {
                $fileName = $file->getFilename();

                // Ignorar arquivos ZIP (backups válidos)
                if (pathinfo($fileName, PATHINFO_EXTENSION) === 'zip') {
                    continue;
                }

                // Deletar todos os arquivos individuais (não ZIP)
                // Arquivos individuais geralmente têm hash MD5 no início do nome (32 caracteres hexadecimais seguidos de _)
                // Exemplo: 0e80ced3f62e7bcab4964fa810a42c7f_vite.config.js
                $shouldDelete = false;

                if ($updateId) {
                    // Se foi especificado um updateId, deletar apenas arquivos relacionados
                    // Verificar se o nome contém o updateId ou se é um arquivo individual (hash MD5)
                    if (strpos($fileName, $updateId) !== false || preg_match('/^[a-f0-9]{32}_/', $fileName)) {
                        $shouldDelete = true;
                    }
                } else {
                    // Sem updateId, deletar TODOS os arquivos individuais (não ZIP)
                    // Padrão: hash MD5 de 32 caracteres seguido de underscore
                    if (preg_match('/^[a-f0-9]{32}_/', $fileName)) {
                        $shouldDelete = true;
                    }
                }

                if ($shouldDelete) {
                    try {
                        File::delete($file->getPathname());
                        $deletedCount++;
                        $deletedFiles[] = $fileName;
                        Log::info('Arquivo de backup individual removido', [
                            'file' => $fileName,
                            'update_id' => $updateId,
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Erro ao deletar arquivo de backup individual', [
                            'file' => $fileName,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            if ($deletedCount > 0) {
                Log::info('Limpeza de arquivos de backup individuais concluída', [
                    'deleted_count' => $deletedCount,
                    'deleted_files' => $deletedFiles,
                    'update_id' => $updateId,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Erro ao limpar arquivos de backup individuais', [
                'error' => $e->getMessage(),
                'update_id' => $updateId,
            ]);
        }
    }

    /**
     * Deletar atualização
     */
    public function deleteUpdate($updateId)
    {
        $update = $this->getUpdate($updateId);

        if (!$update) {
            throw new Exception('Atualização não encontrada.');
        }

        // Deletar arquivo ZIP
        $filePath = $this->updatesPath . '/' . $update['file_name'];
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Deletar backup se existir
        if (isset($update['backup_file'])) {
            $backupPath = $this->backupsPath . '/' . $update['backup_file'];
            if (File::exists($backupPath)) {
                File::delete($backupPath);
            }
        }

        // Limpar arquivos individuais relacionados a esta atualização
        $this->cleanupIndividualBackupFiles($updateId);

        // Remover do registro
        $updatesFile = storage_path('app/updates/updates.json');
        $updates = $this->getUpdateHistory();
        $updates = collect($updates)->reject(function ($u) use ($updateId) {
            return $u['id'] === $updateId;
        })->values()->all();

        File::put($updatesFile, json_encode($updates, JSON_PRETTY_PRINT));

        Log::info('Atualização deletada', [
            'update_id' => $updateId,
            'user_id' => auth()->user()?->id,
        ]);
    }
}

