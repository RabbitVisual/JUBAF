<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ModuleService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    protected $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    protected function routePrefix(): string
    {
        return 'admin.modules';
    }

    protected function viewPrefix(): string
    {
        return 'admin::modules';
    }

    protected function panelLayout(): string
    {
        return 'layouts.app';
    }

    public function index(Request $request)
    {
        $modules = $this->moduleService->getAllModules();
        $overallStats = $this->moduleService->getOverallStats();
        
        // Filtros
        $filter = $request->get('filter', 'all'); // all, enabled, disabled
        $search = $request->get('search', '');
        
        // Aplicar filtros
        if ($filter === 'enabled') {
            $modules = array_filter($modules, fn($m) => $m['enabled']);
        } elseif ($filter === 'disabled') {
            $modules = array_filter($modules, fn($m) => !$m['enabled']);
        }
        
        // Aplicar busca
        if ($search) {
            $modules = array_filter($modules, function ($m) use ($search) {
                return stripos($m['name'], $search) !== false
                    || stripos((string) ($m['alias'] ?? ''), $search) !== false
                    || stripos($m['description'] ?? '', $search) !== false
                    || (is_array($m['keywords'] ?? null) && collect($m['keywords'])
                        ->contains(fn ($kw) => stripos((string) $kw, $search) !== false));
            });
        }
        
        // Reindexar array
        $modules = array_values($modules);
        
        $routePrefix = $this->routePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.index', compact('modules', 'overallStats', 'filter', 'search', 'routePrefix', 'layout'));
    }

    public function show(string $moduleName)
    {
        $modules = collect($this->moduleService->getAllModules());
        $module = $modules->first(fn (array $m) => strcasecmp($m['name'], $moduleName) === 0)
            ?? $modules->first(fn (array $m) => strcasecmp((string) ($m['alias'] ?? ''), $moduleName) === 0);

        if (! $module) {
            return redirect()->route($this->routePrefix().'.index')
                ->with('error', 'Módulo não encontrado');
        }

        $stats = $this->moduleService->getModuleStats($module['name']);
        $adminShortcuts = $this->moduleService->getModuleAdminShortcuts($module['name']);
        $routePrefix = $this->routePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.show', compact('module', 'stats', 'adminShortcuts', 'routePrefix', 'layout'));
    }

    public function enable(string $moduleName)
    {
        $canonical = $this->resolveModuleStudlyName($moduleName) ?? $moduleName;
        $result = $this->moduleService->enableModule($canonical);

        if ($result) {
            return redirect()->route($this->routePrefix().'.index')
                ->with('success', "Módulo {$canonical} habilitado com sucesso");
        }

        return redirect()->route($this->routePrefix().'.index')
            ->with('error', "Erro ao habilitar módulo {$canonical}");
    }

    public function disable(string $moduleName)
    {
        $canonical = $this->resolveModuleStudlyName($moduleName) ?? $moduleName;
        $result = $this->moduleService->disableModule($canonical);

        if ($result) {
            return redirect()->route($this->routePrefix().'.index')
                ->with('success', "Módulo {$canonical} desabilitado com sucesso");
        }

        return redirect()->route($this->routePrefix().'.index')
            ->with('error', "Erro ao desabilitar módulo {$canonical}");
    }

    private function resolveModuleStudlyName(string $moduleName): ?string
    {
        $module = collect($this->moduleService->getAllModules())
            ->first(fn (array $m) => strcasecmp($m['name'], $moduleName) === 0
                || strcasecmp((string) ($m['alias'] ?? ''), $moduleName) === 0);

        return $module['name'] ?? null;
    }
}
