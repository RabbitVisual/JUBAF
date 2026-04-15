<?php

namespace Modules\Permisao\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\PermissionService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    public function __construct(protected PermissionService $permissionService) {}

    protected function routePrefix(): string
    {
        return 'admin.roles';
    }

    protected function permissionsRoutePrefix(): string
    {
        return 'admin.permissions';
    }

    protected function viewPrefix(): string
    {
        return 'permisao::admin.roles';
    }

    protected function panelLayout(): string
    {
        return 'admin::layouts.admin';
    }

    public function index()
    {
        $roles = $this->permissionService->getAllRoles()->load('users');
        $routePrefix = $this->routePrefix();
        $permissionsRoutePrefix = $this->permissionsRoutePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.index', compact('roles', 'routePrefix', 'permissionsRoutePrefix', 'layout'));
    }

    public function create()
    {
        $permissions = $this->permissionService->getPermissionsGrouped();
        $routePrefix = $this->routePrefix();
        $permissionsRoutePrefix = $this->permissionsRoutePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.create', compact('permissions', 'routePrefix', 'permissionsRoutePrefix', 'layout'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $role = $this->permissionService->createRole($validated);

            return redirect()->route($this->routePrefix().'.index')
                ->with('success', "Role {$role->name} criada com sucesso");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar role: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        $role->load('permissions');
        $permissions = $this->permissionService->getPermissionsGrouped();
        $routePrefix = $this->routePrefix();
        $permissionsRoutePrefix = $this->permissionsRoutePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.show', compact('role', 'permissions', 'routePrefix', 'permissionsRoutePrefix', 'layout'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = $this->permissionService->getPermissionsGrouped();
        $role->load('permissions');
        $routePrefix = $this->routePrefix();
        $permissionsRoutePrefix = $this->permissionsRoutePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.edit', compact('role', 'permissions', 'routePrefix', 'permissionsRoutePrefix', 'layout'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $role = $this->permissionService->updateRole($role, $validated);

            return redirect()->route($this->routePrefix().'.index')
                ->with('success', "Role {$role->name} atualizada com sucesso");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar role: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $this->permissionService->deleteRole($role);

            return redirect()->route($this->routePrefix().'.index')
                ->with('success', "Role {$role->name} deletada com sucesso");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao deletar role: '.$e->getMessage());
        }
    }
}
