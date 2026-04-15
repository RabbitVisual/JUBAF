<?php

namespace App\Services\Admin;

use App\Models\AuditLog;
use App\Models\User;
use App\Support\RoleAssignmentGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Modules\Igrejas\App\Events\LeaderAssignedToChurch;

class UserService
{
    /**
     * Get all users with pagination
     */
    public function getAllUsers(array $filters = [], int $perPage = 15)
    {
        $query = User::with('roles');

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $searchClean = preg_replace('/[^0-9]/', '', $search);
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('church_phone', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$searchClean}%");
            });
        }

        if (isset($filters['role'])) {
            $query->role($filters['role']);
        }

        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Create a new user
     *
     * @param  array<string, mixed>  $data
     */
    public function createUser(array $data): User
    {
        DB::beginTransaction();

        try {
            $actor = Auth::user();
            $roles = isset($data['roles']) ? array_values(array_filter((array) $data['roles'])) : [];
            RoleAssignmentGuard::assertActorMayAssignRoles($actor, $roles);

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'],
                'cpf' => isset($data['cpf']) ? preg_replace('/[^0-9]/', '', $data['cpf']) : null,
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'] ?? null,
                'church_phone' => $data['church_phone'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'active' => $data['active'] ?? true,
                'church_id' => $data['church_id'] ?? null,
                'jubaf_sector_id' => $data['jubaf_sector_id'] ?? null,
            ]);

            if ($roles !== []) {
                $user->syncRoles($roles);
            }

            $this->syncUserChurchesFromRequestData($user, $data);
            $user->refresh()->load('assignedChurches');
            $this->dispatchLeaderAssignedEvents($user, []);

            AuditLog::log(
                'user.create',
                User::class,
                $user->id,
                'admin',
                "Usuário {$user->name} criado",
                null,
                $user->toArray()
            );

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update a user
     *
     * @param  array<string, mixed>  $data
     */
    public function updateUser(User $user, array $data): User
    {
        DB::beginTransaction();

        try {
            $actor = Auth::user();
            RoleAssignmentGuard::assertActorMayManageUser($actor, $user);

            $oldValues = $user->toArray();

            $user->load('assignedChurches');
            $previousChurchIds = $this->collectAffiliatedChurchIds($user);

            $updateData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'],
                'cpf' => isset($data['cpf']) && $data['cpf'] !== null && $data['cpf'] !== '' ? preg_replace('/[^0-9]/', '', $data['cpf']) : $user->cpf,
                'phone' => $data['phone'] ?? $user->phone,
                'church_phone' => array_key_exists('church_phone', $data) ? $data['church_phone'] : $user->church_phone,
                'birth_date' => array_key_exists('birth_date', $data) ? $data['birth_date'] : $user->birth_date,
                'active' => $data['active'] ?? $user->active,
                'church_id' => array_key_exists('church_id', $data) ? $data['church_id'] : $user->church_id,
                'jubaf_sector_id' => array_key_exists('jubaf_sector_id', $data) ? $data['jubaf_sector_id'] : $user->jubaf_sector_id,
            ];

            if (isset($data['password']) && $data['password'] !== null && $data['password'] !== '') {
                $updateData['password'] = Hash::make($data['password']);
            }

            if (isset($data['roles'])) {
                $roles = array_values(array_filter((array) $data['roles']));
                RoleAssignmentGuard::assertActorMayAssignRoles($actor, $roles);
                RoleAssignmentGuard::assertNotDemotingLastSuperAdmin($user, $roles);
            }

            $user->update($updateData);

            if (isset($data['roles'])) {
                $roles = array_values(array_filter((array) $data['roles']));
                $user->syncRoles($roles);
            }

            $this->syncUserChurchesFromRequestData($user, $data);
            $user->refresh()->load('assignedChurches');
            $this->dispatchLeaderAssignedEvents($user, $previousChurchIds);

            AuditLog::log(
                'user.update',
                User::class,
                $user->id,
                'admin',
                "Usuário {$user->name} atualizado",
                $oldValues,
                $user->fresh()->toArray()
            );

            DB::commit();

            return $user->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete a user
     */
    public function deleteUser(User $user): bool
    {
        DB::beginTransaction();

        try {
            $actor = Auth::user();
            RoleAssignmentGuard::assertActorMayManageUser($actor, $user);
            RoleAssignmentGuard::assertNotSelfDestruct($actor, $user);
            RoleAssignmentGuard::assertNotDeletingLastSuperAdmin($user);

            $oldValues = $user->toArray();
            $userName = $user->name;

            $user->delete();

            AuditLog::log(
                'user.delete',
                User::class,
                $user->id,
                'admin',
                "Usuário {$userName} deletado",
                $oldValues,
                null
            );

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleUserStatus(User $user): User
    {
        $actor = Auth::user();
        RoleAssignmentGuard::assertActorMayManageUser($actor, $user);
        RoleAssignmentGuard::assertNotSelfDestruct($actor, $user);

        if ($user->active) {
            RoleAssignmentGuard::assertNotDeactivatingLastSuperAdmin($user);
        }

        $oldStatus = $user->active;
        $user->update(['active' => ! $oldStatus]);

        AuditLog::log(
            'user.status',
            User::class,
            $user->id,
            'admin',
            "Status do usuário {$user->name} alterado",
            ['active' => $oldStatus],
            ['active' => ! $oldStatus]
        );

        return $user->fresh();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function syncUserChurchesFromRequestData(User $user, array $data): void
    {
        if (! Schema::hasTable('user_churches')) {
            return;
        }

        $roles = isset($data['roles'])
            ? (array) $data['roles']
            : $user->roles->pluck('name')->all();

        if (count(array_intersect($roles, ['lider', 'pastor'])) === 0) {
            $user->assignedChurches()->detach();

            return;
        }

        $primary = array_key_exists('church_id', $data) ? $data['church_id'] : $user->church_id;
        $extra = array_values(array_filter((array) ($data['assigned_church_ids'] ?? [])));
        $ids = collect([$primary])->merge($extra)->filter()->map(fn ($id) => (int) $id)->unique()->values()->all();

        if ($ids === []) {
            $user->assignedChurches()->detach();

            return;
        }

        $user->assignedChurches()->sync(
            collect($ids)->mapWithKeys(fn (int $id) => [$id => ['role_on_church' => null]])->all()
        );
    }

    /**
     * @return list<int>
     */
    protected function collectAffiliatedChurchIds(User $user): array
    {
        return collect([$user->church_id])
            ->merge($user->assignedChurches->pluck('id'))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param  list<int>  $previousChurchIds
     */
    protected function dispatchLeaderAssignedEvents(User $user, array $previousChurchIds): void
    {
        if (! $user->hasAnyRole(['lider', 'pastor'])) {
            return;
        }

        $prev = collect($previousChurchIds)->map(fn ($id) => (int) $id)->unique();
        $now = collect([$user->church_id])
            ->merge($user->assignedChurches->pluck('id'))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique();

        foreach ($now->diff($prev)->all() as $churchId) {
            event(new LeaderAssignedToChurch($user, (int) $churchId));
        }
    }
}
