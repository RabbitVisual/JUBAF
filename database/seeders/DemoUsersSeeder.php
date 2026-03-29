<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = config('dev_demo_users.accounts', []);

        if ($accounts === []) {
            $this->command?->warn('config/dev_demo_users.php sem contas demo.');

            return;
        }

        foreach ($accounts as $row) {
            $slug = $row['slug'] ?? null;
            $email = $row['email'] ?? null;
            $password = $row['password'] ?? null;
            $label = $row['label'] ?? $slug;

            if (! $slug || ! $email || ! $password) {
                continue;
            }

            $role = Role::where('slug', $slug)->first();
            if (! $role) {
                $this->command?->warn("Papel \"{$slug}\" não encontrado — ignorando {$email}.");

                continue;
            }

            $base = [
                'name' => "Demo {$label}",
                'first_name' => 'Demo',
                'last_name' => $label,
                'email' => $email,
                'password' => Hash::make($password),
                'role_id' => $role->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ];

            if ($slug === 'membro') {
                $base = array_merge($base, [
                    'cpf' => '999.999.999-99',
                    'date_of_birth' => '2005-05-20',
                    'gender' => 'M',
                    'marital_status' => 'solteiro',
                    'phone' => '(75) 1234-5678',
                    'cellphone' => '(75) 9 9876-5432',
                    'address' => 'Rua da Juventude',
                    'address_number' => '100',
                    'neighborhood' => 'Centro',
                    'city' => 'Feira de Santana',
                    'state' => 'BA',
                    'zip_code' => '44001-001',
                ]);
            }

            User::updateOrCreate(
                ['email' => $email],
                $base
            );

            $this->command?->info("✅ Demo {$label}: {$email} / {$password}");
        }
    }
}
