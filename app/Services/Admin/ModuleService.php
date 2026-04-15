<?php

namespace App\Services\Admin;

use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ModuleService
{
    /**
     * Get all modules with their status
     */
    public function getAllModules(): array
    {
        $modules = Module::all();
        $modulesData = [];

        foreach ($modules as $module) {
            $studly = $module->getStudlyName();
            [$author, $company] = $this->normalizeAuthorAndCompany($module);

            $modulesData[] = [
                'name' => $module->getName(),
                'alias' => $module->get('alias', strtolower($studly)),
                'description' => $module->getDescription()
                    ?? $module->get('description', 'Sem descrição'),
                'version' => $module->get('version', '1.0.0'),
                'author' => $author,
                'company' => $company,
                'keywords' => $module->get('keywords', []),
                'enabled' => $module->isEnabled(),
                'path' => $module->getPath(),
                'namespace' => 'Modules\\'.$studly.'\\App',
                'priority' => (int) ($module->getPriority() ?: $module->get('priority', 0)),
            ];
        }

        usort($modulesData, function (array $a, array $b): int {
            if ($a['priority'] !== $b['priority']) {
                return $b['priority'] <=> $a['priority'];
            }

            return strcasecmp($a['name'], $b['name']);
        });

        return $modulesData;
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function normalizeAuthorAndCompany($module): array
    {
        $rawAuthor = $module->get('author', 'N/A');
        $company = $module->get('company', 'N/A');

        if (is_array($rawAuthor)) {
            $author = (string) ($rawAuthor['name'] ?? $rawAuthor['author'] ?? 'N/A');
            if (($company === 'N/A' || $company === '') && ! empty($rawAuthor['company'])) {
                $company = (string) $rawAuthor['company'];
            }
        } else {
            $author = $rawAuthor !== null && $rawAuthor !== '' ? (string) $rawAuthor : 'N/A';
        }

        if ($company === '' || $company === null) {
            $company = 'N/A';
        }

        return [$author, $company];
    }

    /**
     * Links para o painel admin onde o módulo é gerido (quando as rotas existem).
     *
     * @return list<array{label: string, url: string}>
     */
    public function getModuleAdminShortcuts(string $moduleName): array
    {
        $candidates = match ($moduleName) {
            'Blog' => [
                ['label' => 'Gestão do blog', 'route' => 'admin.blog.index'],
            ],
            'Chat' => [
                ['label' => 'Sessões de chat', 'route' => 'admin.chat.index'],
                ['label' => 'Configurações do chat', 'route' => 'admin.chat.config'],
            ],
            'Homepage' => [
                ['label' => 'Editor da homepage', 'route' => 'admin.homepage.index'],
            ],
            'Avisos' => [
                ['label' => 'Avisos e banners', 'route' => 'admin.avisos.index'],
            ],
            'Notificacoes' => [
                ['label' => 'Notificações (admin)', 'route' => 'admin.notificacoes.index'],
            ],
            'Bible' => [
                ['label' => 'Bíblia digital', 'route' => 'admin.bible.index'],
            ],
            default => [],
        };

        $out = [];
        foreach ($candidates as $item) {
            if (Route::has($item['route'])) {
                $out[] = [
                    'label' => $item['label'],
                    'url' => route($item['route']),
                ];
            }
        }

        return $out;
    }

    /**
     * Enable a module
     */
    public function enableModule(string $moduleName): bool
    {
        try {
            $module = Module::find($moduleName);

            if (!$module) {
                return false;
            }

            Module::enable($moduleName);

            try {
                if (class_exists(\App\Models\AuditLog::class) && method_exists(\App\Models\AuditLog::class, 'log')) {
                    \App\Models\AuditLog::log(
                        'module.enable',
                        null,
                        null,
                        'admin',
                        "Módulo {$moduleName} habilitado",
                        ['enabled' => false],
                        ['enabled' => true]
                    );
                }
            } catch (\Exception $e) {
                // Ignorar erros de log de auditoria
            }

            return true;
        } catch (\Exception $e) {
            \Log::error("Error enabling module {$moduleName}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Disable a module
     */
    public function disableModule(string $moduleName): bool
    {
        try {
            $module = Module::find($moduleName);

            if (!$module) {
                return false;
            }

            Module::disable($moduleName);

            try {
                if (class_exists(\App\Models\AuditLog::class) && method_exists(\App\Models\AuditLog::class, 'log')) {
                    \App\Models\AuditLog::log(
                        'module.disable',
                        null,
                        null,
                        'admin',
                        "Módulo {$moduleName} desabilitado",
                        ['enabled' => true],
                        ['enabled' => false]
                    );
                }
            } catch (\Exception $e) {
                // Ignorar erros de log de auditoria
            }

            return true;
        } catch (\Exception $e) {
            \Log::error("Error disabling module {$moduleName}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get module statistics
     */
    public function getModuleStats(string $moduleName): array
    {
        $module = Module::find($moduleName);

        if (!$module) {
            return [];
        }

        // Contar registros em tabelas do módulo (se existirem)
        $stats = [
            'enabled' => $module->isEnabled(),
            'version' => $module->get('version', '1.0.0'),
        ];

        // Adicionar estatísticas específicas por módulo
        try {
            $total = $this->resolveModulePrimaryCount($moduleName);
            if ($total !== null) {
                $stats['total_registros'] = $total;
            }
        } catch (\Exception $e) {
            // Ignorar erros de tabelas não existentes
            \Log::debug("Erro ao obter estatísticas do módulo {$moduleName}: " . $e->getMessage());
        }

        return $stats;
    }

    /**
     * Contagem principal para o cartão "Métricas" do admin/modules/{Nome}.
     */
    private function resolveModulePrimaryCount(string $moduleName): ?int
    {
        if ($moduleName === 'Homepage') {
            if (! Schema::hasTable('system_configs')) {
                return null;
            }

            return (int) DB::table('system_configs')
                ->where(function ($q): void {
                    $q->where('group', 'homepage')
                        ->orWhere('key', 'like', 'homepage%');
                })
                ->count();
        }

        $moduleTables = [
            'Blog' => 'blog_posts',
            'Notificacoes' => 'notifications',
            'Chat' => 'chat_sessions',
            'Avisos' => 'avisos',
            'Bible' => 'bible_versions',
        ];

        if (! isset($moduleTables[$moduleName])) {
            return null;
        }

        $tableName = $moduleTables[$moduleName];
        if (! Schema::hasTable($tableName)) {
            return null;
        }

        $query = DB::table($tableName);
        if (Schema::hasColumn($tableName, 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        return (int) $query->count();
    }

    /**
     * Get overall statistics for modules page
     */
    public function getOverallStats(): array
    {
        try {
            $modules = Module::all();
            
            // Converter para Collection se necessário
            if (!($modules instanceof Collection)) {
                if (is_array($modules)) {
                    $modules = collect($modules);
                } else {
                    // Se for um objeto iterável, converter para array primeiro
                    $modules = collect(iterator_to_array($modules));
                }
            }
            
            $totalModules = $modules->count();
            $enabledModules = $modules->filter(function($m) {
                if (is_object($m) && method_exists($m, 'isEnabled')) {
                    return $m->isEnabled();
                }
                return false;
            })->count();
            
            $disabledModules = $totalModules - $enabledModules;

            return [
                'total' => $totalModules,
                'enabled' => $enabledModules,
                'disabled' => $disabledModules,
                'percentage_enabled' => $totalModules > 0 ? round(($enabledModules / $totalModules) * 100, 1) : 0,
            ];
        } catch (\Exception $e) {
            \Log::error("Erro ao obter estatísticas gerais dos módulos: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            return [
                'total' => 0,
                'enabled' => 0,
                'disabled' => 0,
                'percentage_enabled' => 0,
            ];
        }
    }
}

