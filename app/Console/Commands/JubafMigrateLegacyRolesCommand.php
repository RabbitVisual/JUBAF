<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\JubafRoleRegistry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class JubafMigrateLegacyRolesCommand extends Command
{
    protected $signature = 'jubaf:migrate-legacy-roles
                            {--dry-run : Listar alterações sem gravar}
                            {--to=presidente : Slug Spatie do papel de diretoria a atribuir a quem só tinha co-admin}';

    protected $description = 'Migra utilizadores dos papéis legados admin/co-admin para papéis JUBAF canónicos e remove papéis vazios';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $targetSlug = (string) $this->option('to');
        $directorate = JubafRoleRegistry::directorateRoleNames();

        if (! in_array($targetSlug, $directorate, true)) {
            $this->error("O papel --to= deve ser um cargo de diretoria do Estatuto. Recebido: {$targetSlug}");
            $this->line('Válidos: '.implode(', ', $directorate));

            return 1;
        }

        $targetRole = Role::where('name', $targetSlug)->where('guard_name', 'web')->first();
        if (! $targetRole) {
            $this->error("Papel '{$targetSlug}' não existe na base. Execute os seeders de roles primeiro.");

            return 1;
        }

        $superAdminRole = Role::where('name', 'super-admin')->where('guard_name', 'web')->first();
        if (! $superAdminRole) {
            $this->error('Papel super-admin não encontrado.');

            return 1;
        }

        $report = [];

        $adminUsers = User::role('admin')->get();
        foreach ($adminUsers as $user) {
            $report[] = ['user' => $user->id, 'email' => $user->email, 'action' => 'remove admin → assign super-admin if missing'];
            if (! $dry) {
                DB::transaction(function () use ($user, $superAdminRole): void {
                    $user->removeRole('admin');
                    if (! $user->hasRole('super-admin')) {
                        $user->assignRole($superAdminRole);
                    }
                });
            }
        }

        $coAdminUsers = User::role('co-admin')->get();
        foreach ($coAdminUsers as $user) {
            $hasDirectorate = $user->hasAnyRole($directorate);
            $report[] = [
                'user' => $user->id,
                'email' => $user->email,
                'action' => $hasDirectorate
                    ? 'remove co-admin only (já tem diretoria)'
                    : "remove co-admin → assign {$targetSlug}",
            ];
            if (! $dry) {
                DB::transaction(function () use ($user, $targetRole, $directorate, $hasDirectorate): void {
                    $user->removeRole('co-admin');
                    if (! $hasDirectorate && ! $user->hasAnyRole($directorate)) {
                        $user->assignRole($targetRole);
                    }
                });
            }
        }

        foreach ($report as $row) {
            $this->line("[{$row['user']}] {$row['email']}: {$row['action']}");
        }

        if ($dry) {
            $this->warn('Dry-run: nada foi gravado.');
        } else {
            foreach (['admin', 'co-admin'] as $legacyName) {
                $role = Role::where('name', $legacyName)->where('guard_name', 'web')->first();
                if ($role && $role->users()->count() === 0) {
                    $role->permissions()->detach();
                    $role->delete();
                    $this->info("Papel legado '{$legacyName}' removido (sem utilizadores).");
                }
            }
        }

        $this->info('Concluído. Utilizadores afectados: '.count($report));

        return 0;
    }
}
