<?php

namespace Modules\Permisao\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\Admin\PermissionService;
use Illuminate\Http\Request;

class PermissionManagementController extends Controller
{
    public function __construct(protected PermissionService $permissionService) {}

    protected function routePrefix(): string
    {
        return 'admin.permissions';
    }

    protected function rolesRoutePrefix(): string
    {
        return 'admin.roles';
    }

    protected function viewPrefix(): string
    {
        return 'permisao::admin.permissions';
    }

    protected function panelLayout(): string
    {
        return 'layouts.app';
    }

    public function index()
    {
        $permissions = $this->permissionService->getPermissionsGrouped();
        $totalPermissions = Permission::count();
        $routePrefix = $this->routePrefix();
        $rolesRoutePrefix = $this->rolesRoutePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.index', compact('permissions', 'totalPermissions', 'routePrefix', 'rolesRoutePrefix', 'layout'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        try {
            $permission = $this->permissionService->createPermission($validated['name']);

            return redirect()->route($this->routePrefix().'.index')
                ->with('success', "Permissão {$permission->name} criada com sucesso");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar permissão: '.$e->getMessage());
        }
    }

    public function edit(Permission $permission)
    {
        $routePrefix = $this->routePrefix();
        $rolesRoutePrefix = $this->rolesRoutePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.edit', compact('permission', 'routePrefix', 'rolesRoutePrefix', 'layout'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,'.$permission->id,
        ]);

        try {
            $this->permissionService->updatePermission($permission, $validated['name']);

            return redirect()->route($this->routePrefix().'.index')
                ->with('success', 'Permissão atualizada com sucesso');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            $this->permissionService->deletePermission($permission);

            return redirect()->route($this->routePrefix().'.index')
                ->with('success', 'Permissão excluída com sucesso');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
