<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BackupController extends Controller
{
    public function index()
    {
        $backups = $this->getBackups();
        return view('admin::backup.index', compact('backups'));
    }

    public function create()
    {
        try {
            $backupFileName = 'backup_' . date('Y-m-d_His') . '.sql';
            $backupPath = storage_path('app/backups');

            // Garantir que o diretório existe
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $fullPath = $backupPath . '/' . $backupFileName;

            // Tentar usar mysqldump primeiro (mais rápido e completo)
            // Se não disponível, usar método de queries SQL
            if ($this->canUseMysqldump()) {
                try {
                    $this->createBackupWithMysqldump($fullPath);
                    Log::info('Backup criado usando mysqldump');
                } catch (\Exception $e) {
                    Log::warning('Erro ao usar mysqldump, usando método alternativo', [
                        'error' => $e->getMessage(),
                    ]);
                    // Fallback para método de queries SQL
                    $this->createBackupWithQueries($fullPath);
                }
            } else {
                // Usar método de queries SQL (compatível com qualquer servidor)
                $this->createBackupWithQueries($fullPath);
            }

            Log::info('Backup do banco de dados criado', [
                'file' => $backupFileName,
                'path' => $fullPath,
            ]);

            return redirect()->route('admin.backup.index')
                ->with('success', 'Backup criado com sucesso: ' . $backupFileName);
        } catch (\Exception $e) {
            Log::error('Erro ao criar backup', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Erro ao criar backup: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);

        if (!File::exists($backupPath)) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Arquivo de backup não encontrado');
        }

        return response()->download($backupPath);
    }

    public function restore($filename)
    {
        try {
            $backupPath = storage_path('app/backups/' . $filename);

            if (!File::exists($backupPath)) {
                return redirect()->route('admin.backup.index')
                    ->with('error', 'Arquivo de backup não encontrado');
            }

            // Ler conteúdo do arquivo SQL
            $sql = File::get($backupPath);

            if (empty($sql)) {
                return redirect()->route('admin.backup.index')
                    ->with('error', 'Arquivo de backup está vazio');
            }

            // Executar SQL em transação
            DB::beginTransaction();

            try {
                // Dividir SQL em comandos individuais
                $statements = $this->splitSqlStatements($sql);

                foreach ($statements as $statement) {
                    $statement = trim($statement);
                    if (!empty($statement) && !preg_match('/^--/', $statement)) {
                        DB::statement($statement);
                    }
                }

                DB::commit();

                Log::info('Backup restaurado com sucesso', [
                    'file' => $filename,
                    'user_id' => Auth::id(),
                ]);

                return redirect()->route('admin.backup.index')
                    ->with('success', 'Backup restaurado com sucesso: ' . $filename);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Erro ao restaurar backup', [
                'file' => $filename,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Erro ao restaurar backup: ' . $e->getMessage());
        }
    }

    public function destroy($filename)
    {
        try {
            $backupPath = storage_path('app/backups/' . $filename);

            if (File::exists($backupPath)) {
                File::delete($backupPath);
            }

            return redirect()->route('admin.backup.index')
                ->with('success', 'Backup deletado com sucesso');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao deletar backup: ' . $e->getMessage());
        }
    }

    private function getBackups(): array
    {
        $backupPath = storage_path('app/backups');

        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $files = File::files($backupPath);
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
                'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
            ];
        }

        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $backups;
    }

    /**
     * Verificar se mysqldump está disponível
     */
    private function canUseMysqldump(): bool
    {
        // Verificar se funções de execução estão habilitadas
        if (!function_exists('exec') && !function_exists('shell_exec')) {
            return false;
        }

        // Verificar se mysqldump está disponível
        $command = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'
            ? 'where mysqldump'
            : 'which mysqldump';

        $output = [];
        $returnVar = 0;

        // Usar função global exec() com barra invertida
        if (function_exists('exec')) {
            @\exec($command . ' 2>&1', $output, $returnVar);
        } else {
            // Fallback para shell_exec
            $result = @\shell_exec($command . ' 2>&1');
            $returnVar = $result === null ? 1 : 0;
        }

        return $returnVar === 0;
    }

    /**
     * Criar backup usando mysqldump (método nativo MySQL - mais rápido e completo)
     */
    private function createBackupWithMysqldump($filePath, $dryRun = false)
    {
        $connection = DB::connection();
        $config = $connection->getConfig();

        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? 3306;
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];

        // Secure credential passing via temporary file
        $tempCredentialsFile = tempnam(sys_get_temp_dir(), 'mysql-credentials');
        if ($tempCredentialsFile === false) {
             throw new \Exception('Could not create temporary credentials file');
        }
        
        // Secure permissions immediately (0600)
        chmod($tempCredentialsFile, 0600);
        
        $credentialsContent = "[client]\n";
        $credentialsContent .= "user='" . addcslashes($username, "'\\") . "'\n";
        $credentialsContent .= "password='" . addcslashes($password, "'\\") . "'\n";
        $credentialsContent .= "host='" . addcslashes($host, "'\\") . "'\n";
        $credentialsContent .= "port='" . addcslashes($port, "'\\") . "'\n";
        
        file_put_contents($tempCredentialsFile, $credentialsContent);

        try {
            // Construir comando mysqldump
            // Use --defaults-extra-file for credentials
            $command = sprintf(
                'mysqldump --defaults-extra-file=%s --single-transaction --routines --triggers --events --quick --lock-tables=false %s > %s',
                escapeshellarg($tempCredentialsFile),
                escapeshellarg($database),
                escapeshellarg($filePath)
            );

            if ($dryRun) {
                return $command;
            }

            // Executar comando usando função global
            $output = [];
            $returnVar = 0;

            if (function_exists('exec')) {
                @\exec($command . ' 2>&1', $output, $returnVar);
            } else {
                // Fallback para shell_exec
                $result = @\shell_exec($command . ' 2>&1');
                if ($result === null) {
                    $returnVar = 1;
                    $output = ['Erro ao executar mysqldump: função shell_exec retornou null'];
                } else {
                    $output = explode("\n", trim($result));
                }
            }

            if ($returnVar !== 0) {
                throw new \Exception('Erro ao executar mysqldump: ' . implode("\n", $output));
            }

            if (!File::exists($filePath) || File::size($filePath) === 0) {
                throw new \Exception('Backup criado mas arquivo está vazio ou não existe');
            }

            // Adicionar cabeçalho personalizado ao arquivo
            $content = File::get($filePath);
            $header = "-- Backup do banco de dados: {$database}\n";
            $header .= "-- Data: " . date('Y-m-d H:i:s') . "\n";
            $header .= "-- Gerado pelo sistema JUBAF\n";
            $header .= "-- Método: mysqldump (nativo MySQL)\n\n";

            File::put($filePath, $header . $content);
        } finally {
            // Clean up credentials file
            if (file_exists($tempCredentialsFile)) {
                @unlink($tempCredentialsFile);
            }
        }
    }
    /**
     * Criar backup usando queries SQL (método seguro e compatível)
     * Não depende de exec(), shell_exec() ou mysqldump
     * Otimizado para tabelas grandes usando escrita direta no arquivo
     */
    private function createBackupWithQueries($filePath)
    {
        $connection = DB::connection();
        $database = $connection->getDatabaseName();

        // Abrir arquivo para escrita
        $file = fopen($filePath, 'w');
        if (!$file) {
            throw new \Exception('Não foi possível criar arquivo de backup');
        }

        try {
            // Escrever cabeçalho
            fwrite($file, "-- Backup do banco de dados: {$database}\n");
            fwrite($file, "-- Data: " . date('Y-m-d H:i:s') . "\n");
            fwrite($file, "-- Gerado pelo sistema JUBAF\n");
            fwrite($file, "-- Método: Queries SQL (compatível com qualquer servidor)\n\n");
            fwrite($file, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            // Obter todas as tabelas com informações de tamanho
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $database;
            $totalTables = count($tables);
            $processedTables = 0;
            $totalRowsBackedUp = 0;
            $skippedTables = [];
            $tableStats = [];

            Log::info('Iniciando backup do banco de dados', [
                'database' => $database,
                'total_tables' => $totalTables,
            ]);

            // Obter estatísticas de cada tabela ANTES de processar
            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                try {
                    $rowCount = DB::table($tableName)->count();
                    $tableSize = DB::select("SELECT
                        ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb,
                        data_length,
                        index_length
                        FROM information_schema.TABLES
                        WHERE table_schema = '{$database}'
                        AND table_name = '{$tableName}'");

                    $sizeInfo = !empty($tableSize) ? $tableSize[0] : null;
                    $tableStats[$tableName] = [
                        'rows' => $rowCount,
                        'size_mb' => $sizeInfo->size_mb ?? 0,
                        'data_length' => $sizeInfo->data_length ?? 0,
                    ];
                } catch (\Exception $e) {
                    Log::warning("Erro ao obter estatísticas da tabela", [
                        'table' => $tableName,
                        'error' => $e->getMessage(),
                    ]);
                    $tableStats[$tableName] = ['rows' => 0, 'size_mb' => 0];
                }
            }

            // Log de todas as tabelas encontradas com estatísticas
            Log::info('Tabelas encontradas no banco com estatísticas', [
                'tables' => $tableStats,
                'total_tables' => count($tableStats),
                'total_rows' => array_sum(array_column($tableStats, 'rows')),
                'total_size_mb' => array_sum(array_column($tableStats, 'size_mb')),
            ]);

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                $processedTables++;

                try {
                    // Verificar se a tabela existe
                    $tableExists = DB::select("SHOW TABLES LIKE '{$tableName}'");
                    if (empty($tableExists)) {
                        Log::warning("Tabela não encontrada, pulando", ['table' => $tableName]);
                        $skippedTables[] = $tableName;
                        continue;
                    }

                    // Obter contagem de registros para log
                    $rowCount = DB::table($tableName)->count();

                    Log::info("Processando tabela {$processedTables}/{$totalTables}", [
                        'table' => $tableName,
                        'rows' => $rowCount,
                    ]);

                    // Estrutura da tabela
                    $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                    if (!empty($createTable)) {
                        fwrite($file, "-- ============================================\n");
                        fwrite($file, "-- Estrutura da tabela: {$tableName}\n");
                        fwrite($file, "-- Registros: {$rowCount}\n");
                        fwrite($file, "-- ============================================\n");
                        fwrite($file, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                        fwrite($file, $createTable[0]->{'Create Table'} . ";\n\n");

                        // Dados da tabela - processar em chunks para evitar problemas de memória
                        if ($rowCount > 0) {
                            fwrite($file, "-- Dados da tabela: {$tableName}\n");
                            fwrite($file, "LOCK TABLES `{$tableName}` WRITE;\n");

                            // Processar usando método mais robusto
                            $chunkSize = 500;
                            $processedRows = 0;
                            $columns = null;

                            // Obter colunas e verificar se tem ID
                            $firstRow = DB::table($tableName)->first();
                            if ($firstRow) {
                                $columns = array_keys((array) $firstRow);
                            }

                            // Verificar se a tabela tem coluna 'id'
                            $hasIdColumn = $columns && in_array('id', $columns);

                            if ($hasIdColumn) {
                                // Método 1: Usar cursor com ID (mais eficiente)
                                $lastId = 0;
                                while (true) {
                                    $chunk = DB::table($tableName)
                                        ->where('id', '>', $lastId)
                                        ->orderBy('id')
                                        ->limit($chunkSize)
                                        ->get();

                                    if ($chunk->isEmpty()) {
                                        break;
                                    }

                                    // Atualizar lastId para próxima iteração
                                    $lastRow = $chunk->last();
                                    $lastId = $lastRow->id ?? $lastId;

                                    // Processar chunk
                                    $this->processChunk($file, $tableName, $chunk, $columns, $connection);
                                    $processedRows += $chunk->count();

                                    // Liberar memória
                                    unset($chunk);

                                    // Log de progresso para tabelas grandes
                                    if ($rowCount > 1000 && $processedRows % 1000 == 0) {
                                        Log::info("Progresso backup tabela {$tableName}", [
                                            'processed' => $processedRows,
                                            'total' => $rowCount,
                                            'percentage' => round(($processedRows / $rowCount) * 100, 2) . '%',
                                        ]);
                                    }

                                    // Verificar se processou todos os registros
                                    if ($processedRows >= $rowCount) {
                                        break;
                                    }
                                }
                            } else {
                                // Método 2: Usar offset (para tabelas sem ID)
                                $offset = 0;
                                while ($offset < $rowCount) {
                                    $chunk = DB::table($tableName)
                                        ->offset($offset)
                                        ->limit($chunkSize)
                                        ->get();

                                    if ($chunk->isEmpty()) {
                                        break;
                                    }

                                    // Processar chunk
                                    $this->processChunk($file, $tableName, $chunk, $columns, $connection);
                                    $processedRows += $chunk->count();
                                    $offset += $chunkSize;

                                    // Liberar memória
                                    unset($chunk);

                                    // Log de progresso para tabelas grandes
                                    if ($rowCount > 1000 && $processedRows % 1000 == 0) {
                                        Log::info("Progresso backup tabela {$tableName}", [
                                            'processed' => $processedRows,
                                            'total' => $rowCount,
                                            'percentage' => round(($processedRows / $rowCount) * 100, 2) . '%',
                                        ]);
                                    }
                                }
                            }


                            fwrite($file, "UNLOCK TABLES;\n\n");

                            $totalRowsBackedUp += $processedRows;
                            Log::info("Tabela {$tableName} processada com sucesso", [
                                'rows_backed_up' => $processedRows,
                                'expected_rows' => $rowCount,
                                'match' => $processedRows == $rowCount ? 'OK' : 'DIVERGENTE',
                            ]);
                        } else {
                            fwrite($file, "-- Tabela vazia\n\n");
                        }
                    }
                } catch (\Exception $e) {
                    // Continuar com próxima tabela se houver erro
                    Log::error('Erro ao fazer backup da tabela', [
                        'table' => $tableName,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    fwrite($file, "-- ERRO ao fazer backup da tabela {$tableName}: " . $e->getMessage() . "\n\n");
                }
            }

            fwrite($file, "SET FOREIGN_KEY_CHECKS=1;\n");
            fwrite($file, "-- Backup concluído em " . date('Y-m-d H:i:s') . "\n");

            // Adicionar resumo no final do arquivo SQL ANTES de fechar
            $expectedTotalRows = array_sum(array_column($tableStats, 'rows'));
            $expectedTotalSizeMB = array_sum(array_column($tableStats, 'size_mb'));

            fwrite($file, "\n-- ============================================\n");
            fwrite($file, "-- RESUMO DO BACKUP\n");
            fwrite($file, "-- ============================================\n");
            fwrite($file, "-- Tabelas processadas: {$processedTables}/{$totalTables}\n");
            fwrite($file, "-- Registros processados: {$totalRowsBackedUp}\n");
            fwrite($file, "-- Registros esperados: {$expectedTotalRows}\n");
            if (count($skippedTables) > 0) {
                fwrite($file, "-- Tabelas puladas: " . implode(', ', $skippedTables) . "\n");
            }
            fwrite($file, "-- ============================================\n");

        } finally {
            // Fechar arquivo apenas no finally
            if (is_resource($file)) {
                fclose($file);
            }
        }

        // Verificar se o arquivo foi criado corretamente
        if (!File::exists($filePath)) {
            throw new \Exception('Erro ao criar arquivo de backup');
        }

        $fileSize = File::size($filePath);
        if ($fileSize === 0) {
            throw new \Exception('Arquivo de backup está vazio');
        }

        // Calcular estatísticas finais (após fechar o arquivo)
        $expectedTotalRows = array_sum(array_column($tableStats, 'rows'));
        $expectedTotalSizeMB = array_sum(array_column($tableStats, 'size_mb'));

        Log::info('Backup do banco de dados concluído', [
            'file' => basename($filePath),
            'size' => $fileSize,
            'size_mb' => round($fileSize / 1024 / 1024, 2),
            'size_kb' => round($fileSize / 1024, 2),
            'total_tables' => $totalTables,
            'processed_tables' => $processedTables,
            'skipped_tables' => count($skippedTables),
            'skipped_table_names' => $skippedTables,
            'total_rows_backed_up' => $totalRowsBackedUp,
            'expected_total_rows' => $expectedTotalRows,
            'rows_coverage' => $expectedTotalRows > 0 ? round(($totalRowsBackedUp / $expectedTotalRows) * 100, 2) . '%' : 'N/A',
            'expected_total_size_mb' => round($expectedTotalSizeMB, 2),
            'size_coverage' => $expectedTotalSizeMB > 0 ? round((($fileSize / 1024 / 1024) / $expectedTotalSizeMB) * 100, 2) . '%' : 'N/A',
        ]);
    }

    /**
     * Dividir SQL em comandos individuais
     */
    private function splitSqlStatements($sql)
    {
        // Remover comentários de linha única
        $sql = preg_replace('/--.*$/m', '', $sql);

        // Dividir por ponto e vírgula, mas preservar dentro de strings
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';

        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];

            if (!$inString && ($char === '"' || $char === "'" || $char === '`')) {
                $inString = true;
                $stringChar = $char;
                $current .= $char;
            } elseif ($inString && $char === $stringChar) {
                // Verificar se não é escape
                if ($i > 0 && $sql[$i - 1] !== '\\') {
                    $inString = false;
                    $stringChar = '';
                }
                $current .= $char;
            } elseif (!$inString && $char === ';') {
                $statements[] = trim($current);
                $current = '';
            } else {
                $current .= $char;
            }
        }

        // Adicionar último statement se não terminou com ;
        if (!empty(trim($current))) {
            $statements[] = trim($current);
        }

        return array_filter($statements, function($stmt) {
            return !empty(trim($stmt));
        });
    }

    /**
     * Processar chunk de dados e escrever no arquivo
     */
    private function processChunk($file, $tableName, $chunk, $columns, $connection)
    {
        if (empty($columns) || $chunk->isEmpty()) {
            return;
        }

        $values = [];
        foreach ($chunk as $row) {
            $rowArray = (array) $row;
            $escapedValues = [];

            foreach (array_values($rowArray) as $value) {
                if ($value === null) {
                    $escapedValues[] = 'NULL';
                } elseif (is_resource($value)) {
                    // Recursos não podem ser serializados
                    $escapedValues[] = 'NULL';
                } elseif (is_array($value) || is_object($value)) {
                    // Serializar arrays e objetos
                    $escapedValues[] = $connection->getPdo()->quote(json_encode($value));
                } elseif (is_bool($value)) {
                    // Converter boolean para 0 ou 1
                    $escapedValues[] = $value ? '1' : '0';
                } else {
                    // Escapar string normalmente
                    try {
                        $escapedValues[] = $connection->getPdo()->quote($value);
                    } catch (\Exception $e) {
                        // Se falhar ao escapar, tentar converter para string
                        Log::warning("Erro ao escapar valor na tabela {$tableName}", [
                            'error' => $e->getMessage(),
                        ]);
                        $escapedValues[] = $connection->getPdo()->quote((string) $value);
                    }
                }
            }

            $values[] = '(' . implode(',', $escapedValues) . ')';
        }

        if (!empty($values)) {
            $columnsStr = '`' . implode('`,`', $columns) . '`';
            $valuesStr = implode(",\n", $values);

            fwrite($file, "INSERT INTO `{$tableName}` ({$columnsStr}) VALUES\n{$valuesStr};\n");
        }
    }
}
