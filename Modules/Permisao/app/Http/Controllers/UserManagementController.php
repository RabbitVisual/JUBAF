<?php

namespace Modules\Permisao\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\UserService;
use App\Support\RoleAssignmentGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\JubafSector;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function __construct(protected UserService $userService) {}

    protected function routePrefix(): string
    {
        return 'admin.users';
    }

    protected function viewPrefix(): string
    {
        return 'permisao::admin.users';
    }

    protected function panelLayout(): string
    {
        return 'admin::layouts.admin';
    }

    /**
     * @return \Illuminate\Support\Collection<int, Role>
     */
    protected function rolesAssignable(): \Illuminate\Support\Collection
    {
        $actor = auth()->user();

        return Role::orderBy('name')->get()->filter(function (Role $role) use ($actor) {
            if ($role->name === 'super-admin' && ! RoleAssignmentGuard::actorIsSuperAdmin($actor)) {
                return false;
            }

            return true;
        })->values();
    }

    protected function assertMayManage(User $target): void
    {
        try {
            RoleAssignmentGuard::assertActorMayManageUser(auth()->user(), $target);
        } catch (\InvalidArgumentException $e) {
            abort(403, $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'role', 'active']);
        $users = $this->userService->getAllUsers($filters);
        $roles = Role::orderBy('name')->get();
        $routePrefix = $this->routePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.index', compact('users', 'roles', 'filters', 'routePrefix', 'layout'));
    }

    public function create()
    {
        $roles = $this->rolesAssignable();
        $routePrefix = $this->routePrefix();
        $layout = $this->panelLayout();
        $churches = $this->churchesForForm();
        $jubafSectors = $this->jubafSectorsForForm();

        return view($this->viewPrefix().'.create', compact('roles', 'routePrefix', 'layout', 'churches', 'jubafSectors'));
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Modules\Igrejas\App\Models\Church>
     */
    protected function churchesForForm(): \Illuminate\Support\Collection
    {
        if (! module_enabled('Igrejas') || ! Schema::hasTable('igrejas_churches')) {
            return collect();
        }

        return Church::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'city']);
    }

    /**
     * @return \Illuminate\Support\Collection<int, JubafSector>
     */
    protected function jubafSectorsForForm(): \Illuminate\Support\Collection
    {
        if (! Schema::hasTable('jubaf_sectors')) {
            return collect();
        }

        return JubafSector::query()->orderBy('name')->get(['id', 'name', 'slug']);
    }

    /**
     * @return array<string, mixed>
     */
    protected function jubafSectorRules(): array
    {
        if (! Schema::hasTable('jubaf_sectors')) {
            return [];
        }

        return [
            'jubaf_sector_id' => ['nullable', 'integer', Rule::exists('jubaf_sectors', 'id')],
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function normalizeJubafSectorOnUser(array $validated): array
    {
        $roles = array_values(array_filter((array) ($validated['roles'] ?? [])));
        if (count(array_intersect($roles, ['vice-presidente-1', 'vice-presidente-2'])) === 0) {
            $validated['jubaf_sector_id'] = null;
        }

        return $validated;
    }

    public function store(Request $request)
    {
        if ($request->has('cpf')) {
            $cpfClean = preg_replace('/[^0-9]/', '', $request->cpf ?? '');
            $request->merge([
                'cpf' => ! empty($cpfClean) ? $cpfClean : null,
            ]);
        }

        $request->merge(['active' => $request->boolean('active')]);

        $validated = $request->validate(array_merge([
            'first_name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'email' => 'required|string|email|max:255|unique:users',
            'cpf' => 'nullable|string|size:11|unique:users,cpf',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:32',
            'church_phone' => 'nullable|string|max:32',
            'birth_date' => 'nullable|date',
            'active' => 'boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ], $this->churchIdRules($request), $this->jubafSectorRules()));

        $validated = $this->normalizeChurchIdOnUser($validated);
        $validated = $this->normalizeJubafSectorOnUser($validated);
        $validated['assigned_church_ids'] = $this->normalizeAssignedChurchIds($validated);

        try {
            $user = $this->userService->createUser($validated);

            return redirect()->route($this->routePrefix().'.index')
                ->with('success', "Usuário {$user->name} criado com sucesso");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar usuário: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $this->assertMayManage($user);
        $user->load(['roles', 'profilePhotos']);

        $auditLogs = collect([]);
        try {
            if (class_exists(\App\Models\AuditLog::class) && Schema::hasTable('audit_logs')) {
                $auditLogs = \App\Models\AuditLog::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(20)
                    ->get();
            }
        } catch (\Exception $e) {
            // Ignorar erros de audit logs
        }

        $routePrefix = $this->routePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.show', compact('user', 'auditLogs', 'routePrefix', 'layout'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->assertMayManage($user);
        $roles = $this->rolesAssignable();
        $user->load(['roles', 'assignedChurches']);
        $routePrefix = $this->routePrefix();
        $layout = $this->panelLayout();

        $churches = $this->churchesForForm();
        $jubafSectors = $this->jubafSectorsForForm();

        return view($this->viewPrefix().'.edit', compact('user', 'roles', 'routePrefix', 'layout', 'churches', 'jubafSectors'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->assertMayManage($user);

        if ($request->has('cpf')) {
            $cpfClean = preg_replace('/[^0-9]/', '', $request->cpf ?? '');
            $request->merge([
                'cpf' => ! empty($cpfClean) ? $cpfClean : null,
            ]);
        }

        $request->merge(['active' => $request->boolean('active')]);

        $validated = $request->validate(array_merge([
            'first_name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'cpf' => 'nullable|string|size:11|unique:users,cpf,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:32',
            'church_phone' => 'nullable|string|max:32',
            'birth_date' => 'nullable|date',
            'active' => 'boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ], $this->churchIdRules($request), $this->jubafSectorRules()));

        $validated = $this->normalizeChurchIdOnUser($validated);
        $validated = $this->normalizeJubafSectorOnUser($validated);
        $validated['assigned_church_ids'] = $this->normalizeAssignedChurchIds($validated);

        try {
            $user = $this->userService->updateUser($user, $validated);

            return redirect()->route($this->routePrefix().'.index')
                ->with('success', "Usuário {$user->name} atualizado com sucesso");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar usuário: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $name = $user->name;
            $this->userService->deleteUser($user);

            return redirect()->route($this->routePrefix().'.index')
                ->with('success', "Usuário {$name} deletado com sucesso");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao deletar usuário: '.$e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            $user = $this->userService->toggleUserStatus($user);
            $status = $user->active ? 'ativado' : 'desativado';

            return redirect()->back()
                ->with('success', "Usuário {$status} com sucesso");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao alterar status do usuário: '.$e->getMessage());
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function churchIdRules(Request $request): array
    {
        if (! module_enabled('Igrejas') || ! Schema::hasTable('igrejas_churches')) {
            return [];
        }

        $roles = array_values(array_filter((array) $request->input('roles', [])));
        $needsPrimaryChurch = count(array_intersect($roles, ['lider', 'jovens'])) > 0;

        return [
            'church_id' => [
                $needsPrimaryChurch ? 'required' : 'nullable',
                'integer',
                Rule::exists('igrejas_churches', 'id'),
            ],
            'assigned_church_ids' => ['nullable', 'array'],
            'assigned_church_ids.*' => ['integer', Rule::exists('igrejas_churches', 'id')],
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function normalizeChurchIdOnUser(array $validated): array
    {
        $roles = array_values(array_filter((array) ($validated['roles'] ?? [])));
        if (count(array_intersect($roles, ['lider', 'jovens', 'pastor'])) === 0) {
            $validated['church_id'] = null;
            $validated['assigned_church_ids'] = [];
        }

        return $validated;
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return list<int>
     */
    protected function normalizeAssignedChurchIds(array $validated): array
    {
        return array_values(array_unique(array_map(
            'intval',
            array_filter((array) ($validated['assigned_church_ids'] ?? []))
        )));
    }

    public function profile()
    {
        $user = auth()->user();
        $user->load(['roles', 'permissions', 'church', 'profilePhotos']);

        return view('admin::profile.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        if ($request->has('cpf')) {
            $rawCpf = $request->input('cpf');
            $request->merge([
                'cpf' => ($rawCpf === null || $rawCpf === '') ? null : preg_replace('/\D/', '', (string) $rawCpf),
            ]);
        }

        $validated = $request->validate(array_merge([
            'first_name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'cpf' => ['nullable', 'string', 'size:11', Rule::unique('users', 'cpf')->ignore($user->id)],
            'phone' => 'nullable|string|max:32',
            'church_phone' => 'nullable|string|max:32',
            'birth_date' => 'nullable|date',
            'emergency_contact_name' => 'nullable|string|max:120',
            'emergency_contact_phone' => 'nullable|string|max:32',
            'emergency_contact_relationship' => 'nullable|string|max:80',
            'password' => 'nullable|string|min:8|confirmed',
        ], \App\Services\UserProfileMediaService::validationRules($user->profilePhotos()->count())));

        try {
            unset($validated['cover_photo'], $validated['profile_photos'], $validated['photo'], $validated['cover_position_x'], $validated['cover_position_y']);

            if (isset($validated['password']) && ! empty($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            app(\App\Services\UserProfileMediaService::class)->handleProfileFormUploads($user->fresh(), $request);

            return redirect()->route('admin.profile')
                ->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar perfil: '.$e->getMessage());
        }
    }
}
