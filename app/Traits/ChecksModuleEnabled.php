<?php

namespace App\Traits;

use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Schema;

trait ChecksModuleEnabled
{
    /**
     * Verifica se um módulo está habilitado
     *
     * @param string $moduleName Nome do módulo
     * @return bool
     */
    protected function isModuleEnabled(string $moduleName): bool
    {
        try {
            $module = Module::find($moduleName);
            return $module && $module->isEnabled();
        } catch (\Exception $e) {
            \Log::warning("Erro ao verificar status do módulo {$moduleName}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se uma tabela existe no banco de dados
     *
     * @param string $tableName Nome da tabela
     * @return bool
     */
    protected function tableExists(string $tableName): bool
    {
        try {
            return Schema::hasTable($tableName);
        } catch (\Exception $e) {
            \Log::warning("Erro ao verificar existência da tabela {$tableName}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se um módulo está habilitado e retorna erro 503 se não estiver
     *
     * @param string $moduleName Nome do módulo
     * @return bool|void
     */
    protected function ensureModuleEnabled(string $moduleName)
    {
        if (!$this->isModuleEnabled($moduleName)) {
            abort(503, "O módulo {$moduleName} está desabilitado.");
        }
        return true;
    }

    /**
     * Verifica se uma tabela existe e retorna erro 503 se não existir
     *
     * @param string $tableName Nome da tabela
     * @return bool|void
     */
    protected function ensureTableExists(string $tableName)
    {
        if (!$this->tableExists($tableName)) {
            abort(503, "A tabela {$tableName} não existe.");
        }
        return true;
    }

    /**
     * Verifica se um módulo está habilitado e uma tabela existe
     *
     * @param string $moduleName Nome do módulo
     * @param string $tableName Nome da tabela
     * @return bool|void
     */
    protected function ensureModuleAndTable(string $moduleName, string $tableName)
    {
        $this->ensureModuleEnabled($moduleName);
        $this->ensureTableExists($tableName);
        return true;
    }
}

