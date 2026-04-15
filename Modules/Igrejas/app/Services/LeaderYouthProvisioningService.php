<?php

namespace Modules\Igrejas\App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

final class LeaderYouthProvisioningService
{
    /**
     * @param  array{first_name: string, last_name?: string|null, email: string, phone?: string|null, active?: bool, set_password_now?: bool, password?: string|null}  $data
     */
    public function create(User $leader, array $data): User
    {
        if (! $leader->church_id) {
            throw new \InvalidArgumentException('Líder sem igreja associada.');
        }

        return DB::transaction(function () use ($leader, $data): User {
            $setNow = ! empty($data['set_password_now']);
            if ($setNow) {
                $plainPassword = (string) ($data['password'] ?? '');
                if ($plainPassword === '') {
                    throw new \InvalidArgumentException('Palavra-passe obrigatória quando escolhe definir agora.');
                }
            } else {
                $plainPassword = Str::random(64);
            }

            $user = User::query()->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => $plainPassword,
                'active' => array_key_exists('active', $data) ? (bool) $data['active'] : true,
                'church_id' => $leader->church_id,
            ]);
            $user->assignRole('jovens');

            if (! $setNow) {
                Password::broker()->sendResetLink(['email' => $user->email]);
            }

            return $user->fresh(['roles']);
        });
    }

    /**
     * @param  array{first_name: string, last_name?: string|null, email: string, phone?: string|null, active?: bool}  $data
     */
    public function update(User $leader, User $youth, array $data): User
    {
        return DB::transaction(function () use ($leader, $youth, $data): User {
            $youth->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'active' => array_key_exists('active', $data) ? (bool) $data['active'] : $youth->active,
                'church_id' => $leader->church_id,
            ]);

            return $youth->fresh(['roles']);
        });
    }

    public function sendPasswordResetEmail(User $youth): void
    {
        Password::broker()->sendResetLink(['email' => $youth->email]);
    }
}
