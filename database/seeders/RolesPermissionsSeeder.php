<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    private function seedPermission(string $name): void
    {
        Permission::updateOrCreate(
            ['name' => $name, 'guard_name' => 'web'],
            ['is_system' => true]
        );
    }

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modulos = [
            'blog',
            'homepage',
            'notificacoes',
            'chat',
            'avisos',
        ];

        $acoes = ['view', 'create', 'edit', 'delete', 'approve'];

        foreach ($modulos as $modulo) {
            foreach ($acoes as $acao) {
                $this->seedPermission("{$modulo}.{$acao}");
            }
        }

        $this->seedPermission('admin.*');
        $this->seedPermission('usuarios.manage');
        $this->seedPermission('sistema.config');
        $this->seedPermission('homepage.view');
        $this->seedPermission('carousel.view');
        $this->seedPermission('admin.dashboard.view');
        $this->seedPermission('audit.view');

        foreach (['view', 'create', 'edit', 'delete'] as $boardAction) {
            $this->seedPermission('board_members.'.$boardAction);
        }

        foreach (['view', 'create', 'edit', 'delete', 'publish'] as $d) {
            $this->seedPermission('devotionals.'.$d);
        }

        foreach (['view', 'create', 'edit', 'delete', 'activate'] as $ig) {
            $this->seedPermission('igrejas.'.$ig);
        }
        $this->seedPermission('igrejas.requests.submit');
        $this->seedPermission('igrejas.requests.review');
        $this->seedPermission('igrejas.jovens.provision');

        $this->seedPermission('secretaria.dashboard.view');

        foreach (['meetings', 'minutes', 'convocations', 'documents'] as $entity) {
            foreach (['view', 'create', 'edit', 'delete'] as $a) {
                $this->seedPermission("secretaria.{$entity}.{$a}");
            }
        }

        $this->seedPermission('secretaria.minutes.submit');
        $this->seedPermission('secretaria.minutes.request_signatures');
        $this->seedPermission('secretaria.minutes.sign');
        foreach (['approve', 'publish'] as $a) {
            $this->seedPermission('secretaria.minutes.'.$a);
            $this->seedPermission('secretaria.convocations.'.$a);
        }

        $this->seedPermission('secretaria.settings.manage');

        foreach (['view', 'create', 'edit', 'delete'] as $a) {
            $this->seedPermission("financeiro.transactions.{$a}");
        }
        foreach (['view', 'create', 'edit', 'delete', 'approve', 'pay'] as $a) {
            $this->seedPermission("financeiro.expense_requests.{$a}");
        }
        $this->seedPermission('financeiro.reports.view');
        $this->seedPermission('financeiro.dashboard.view');
        $this->seedPermission('financeiro.categories.view');
        $this->seedPermission('financeiro.categories.manage');
        $this->seedPermission('financeiro.obligations.view');
        $this->seedPermission('financeiro.obligations.manage');
        $this->seedPermission('financeiro.minhas_contas.view');

        $this->seedPermission('gateway.dashboard.view');
        $this->seedPermission('gateway.payments.view');
        $this->seedPermission('gateway.reports.view');
        $this->seedPermission('gateway.accounts.manage');

        foreach (['view', 'create', 'edit', 'delete'] as $a) {
            $this->seedPermission("calendario.events.{$a}");
        }
        foreach (['view', 'edit', 'delete'] as $a) {
            $this->seedPermission("calendario.registrations.{$a}");
        }
        $this->seedPermission('calendario.participate');

        $this->seedPermission('talentos.profile.edit');
        $this->seedPermission('talentos.directory.view');
        $this->seedPermission('talentos.directory.export');
        foreach (['view', 'create', 'edit', 'delete'] as $a) {
            $this->seedPermission("talentos.assignments.{$a}");
        }
        $this->seedPermission('talentos.taxonomy.manage');

        $allNames = Permission::pluck('name')->all();

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $presidente = Role::firstOrCreate(['name' => 'presidente', 'guard_name' => 'web']);
        $vice1 = Role::firstOrCreate(['name' => 'vice-presidente-1', 'guard_name' => 'web']);
        $vice2 = Role::firstOrCreate(['name' => 'vice-presidente-2', 'guard_name' => 'web']);
        $secretario1 = Role::firstOrCreate(['name' => 'secretario-1', 'guard_name' => 'web']);
        $secretario2 = Role::firstOrCreate(['name' => 'secretario-2', 'guard_name' => 'web']);
        $tesoureiro1 = Role::firstOrCreate(['name' => 'tesoureiro-1', 'guard_name' => 'web']);
        $tesoureiro2 = Role::firstOrCreate(['name' => 'tesoureiro-2', 'guard_name' => 'web']);
        $pastor = Role::firstOrCreate(['name' => 'pastor', 'guard_name' => 'web']);
        $lider = Role::firstOrCreate(['name' => 'lider', 'guard_name' => 'web']);
        $jovens = Role::firstOrCreate(['name' => 'jovens', 'guard_name' => 'web']);

        $superAdmin->syncPermissions($allNames);

        $financeiroReadOnly = [
            'financeiro.dashboard.view',
            'financeiro.transactions.view',
            'financeiro.expense_requests.view',
            'financeiro.reports.view',
            'financeiro.categories.view',
            'financeiro.obligations.view',
        ];

        $presidentePerms = array_values(array_filter(
            $allNames,
            function (string $n) use ($financeiroReadOnly) {
                if (in_array($n, ['admin.*', 'usuarios.manage', 'sistema.config', 'gateway.accounts.manage'], true)) {
                    return false;
                }
                if (str_starts_with($n, 'financeiro.') && ! in_array($n, $financeiroReadOnly, true)) {
                    return false;
                }

                return true;
            }
        ));
        $presidente->syncPermissions($presidentePerms);

        $vicePerms = array_values(array_filter(
            $presidentePerms,
            fn (string $n) => ! in_array($n, [
                'audit.view',
                'blog.delete',
                'notificacoes.delete',
                'financeiro.obligations.manage',
            ], true)
        ));
        $vice1->syncPermissions($vicePerms);
        $vice2->syncPermissions($vicePerms);

        $secretarioBase = array_values(array_filter(
            $allNames,
            function (string $n) {
                foreach (['blog.', 'avisos.', 'notificacoes.', 'chat.', 'homepage.', 'board_members.', 'devotionals.'] as $prefix) {
                    if (str_starts_with($n, $prefix)) {
                        return true;
                    }
                }

                return $n === 'carousel.view';
            }
        ));

        $secretarioIgrejas = array_values(array_filter(
            $allNames,
            fn (string $n) => str_starts_with($n, 'igrejas.')
                && ! in_array($n, ['igrejas.delete', 'igrejas.activate'], true)
        ));

        $secretarioSecretaria = array_values(array_filter(
            $allNames,
            fn (string $n) => str_starts_with($n, 'secretaria.')
                && $n !== 'secretaria.settings.manage'
                && ! str_ends_with($n, '.approve')
                && ! str_ends_with($n, '.publish')
        ));

        $secretarioFinanceRead = array_values(array_filter(
            $allNames,
            fn (string $n) => in_array($n, [
                'financeiro.dashboard.view',
                'financeiro.transactions.view',
                'financeiro.expense_requests.view',
                'financeiro.reports.view',
                'financeiro.categories.view',
                'financeiro.obligations.view',
                'gateway.dashboard.view',
                'gateway.payments.view',
            ], true)
        ));

        $secretarioCalendarioTalentos = array_values(array_filter(
            $allNames,
            fn (string $n) => str_starts_with($n, 'calendario.') || str_starts_with($n, 'talentos.')
        ));

        $secretarioPerms = array_values(array_unique(array_merge(
            $secretarioBase,
            $secretarioIgrejas,
            $secretarioSecretaria,
            $secretarioFinanceRead,
            $secretarioCalendarioTalentos
        )));
        $secretario1->syncPermissions($secretarioPerms);
        $secretario2->syncPermissions($secretarioPerms);

        $tesoureiroBaseViews = array_values(array_filter(
            $allNames,
            fn (string $n) => str_ends_with($n, '.view') || $n === 'carousel.view'
        ));

        $tesoureiroFinance = array_values(array_filter(
            $allNames,
            fn (string $n) => (str_starts_with($n, 'financeiro.')
                && $n !== 'financeiro.expense_requests.approve')
                || (str_starts_with($n, 'gateway.') && $n !== 'gateway.accounts.manage')
        ));

        $tesoureiroCalTal = array_values(array_filter(
            $allNames,
            fn (string $n) => (str_starts_with($n, 'calendario.') && str_ends_with($n, '.view'))
                || $n === 'talentos.directory.view'
        ));

        $tesoureiroPerms = array_values(array_unique(array_merge(
            $tesoureiroBaseViews,
            $tesoureiroFinance,
            $tesoureiroCalTal
        )));
        $tesoureiro1->syncPermissions($tesoureiroPerms);
        $tesoureiro2->syncPermissions($tesoureiroPerms);

        $pastorBase = array_values(array_filter(
            $allNames,
            fn (string $n) => (str_ends_with($n, '.view') || $n === 'carousel.view')
                && ! str_starts_with($n, 'financeiro.')
        ));
        $pastorPerms = array_values(array_unique(array_merge($pastorBase, ['financeiro.minhas_contas.view'])));
        $pastor->syncPermissions($pastorPerms);

        $permissionsLider = [
            'chat.view',
            'notificacoes.view',
            'secretaria.minutes.view',
            'secretaria.convocations.view',
            'secretaria.documents.view',
            'calendario.participate',
            'talentos.profile.edit',
            'igrejas.requests.submit',
            'igrejas.jovens.provision',
            'financeiro.minhas_contas.view',
            'secretaria.minutes.sign',
        ];
        $lider->syncPermissions(Permission::whereIn('name', $permissionsLider)->pluck('name')->toArray());

        $permissionsForJovens = [];
        foreach ($modulos as $modulo) {
            $permissionsForJovens[] = "{$modulo}.view";
        }
        $permissionsForJovens = array_merge($permissionsForJovens, [
            'homepage.view',
            'carousel.view',
            'admin.dashboard.view',
            'audit.view',
            'secretaria.minutes.view',
            'secretaria.convocations.view',
            'secretaria.documents.view',
            'calendario.participate',
            'talentos.profile.edit',
        ]);
        $jovens->syncPermissions(Permission::whereIn('name', $permissionsForJovens)->pluck('name')->toArray());

        DB::transaction(function () use ($superAdmin, $presidente) {
            $legacyAdmin = Role::where('name', 'admin')->first();
            if ($legacyAdmin) {
                foreach (User::role('admin')->get() as $user) {
                    $user->removeRole('admin');
                    if (! $user->hasRole('super-admin')) {
                        $user->assignRole($superAdmin);
                    }
                }
            }

            $directorateSlugs = [
                'presidente',
                'vice-presidente-1',
                'vice-presidente-2',
                'secretario-1',
                'secretario-2',
                'tesoureiro-1',
                'tesoureiro-2',
            ];

            $legacyCo = Role::where('name', 'co-admin')->first();
            if ($legacyCo) {
                foreach (User::role('co-admin')->get() as $user) {
                    $user->removeRole('co-admin');
                    if (! $user->hasAnyRole($directorateSlugs)) {
                        $user->assignRole($presidente);
                    }
                }
            }

            foreach (['admin', 'co-admin'] as $legacyName) {
                $role = Role::where('name', $legacyName)->first();
                if ($role && $role->users()->count() === 0) {
                    $role->permissions()->detach();
                    $role->delete();
                }
            }
        });

        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@jubaf.local'],
            [
                'first_name' => 'Super',
                'last_name' => 'Administrador JUBAF',
                'password' => bcrypt('admin123'),
                'active' => true,
            ]
        );
        $userAdmin->syncRoles(['super-admin']);

        $userCoAdmin = User::firstOrCreate(
            ['email' => 'coadmin@jubaf.local'],
            [
                'first_name' => 'Presidente',
                'last_name' => '(demo)',
                'password' => bcrypt('coadmin123'),
                'active' => true,
            ]
        );
        $userCoAdmin->syncRoles(['presidente']);

        $userJovens = User::firstOrCreate(
            ['email' => 'jovens@jubaf.local'],
            [
                'first_name' => 'Jovem',
                'last_name' => 'JUBAF (demo)',
                'password' => bcrypt('jovens123'),
                'active' => true,
            ]
        );
        $userJovens->syncRoles(['jovens']);

        $legacyConsultaDemo = User::where('email', 'consulta@jubaf.local')->first();
        if ($legacyConsultaDemo) {
            $legacyConsultaDemo->syncRoles(['jovens']);
        }

        $userLider = User::firstOrCreate(
            ['email' => 'lider@jubaf.local'],
            [
                'first_name' => 'Líder',
                'last_name' => 'JUBAF (demo)',
                'password' => bcrypt('lider123'),
                'active' => true,
            ]
        );
        $userLider->syncRoles(['lider']);

        $legacyDemoLiderEmail = 'campo@jubaf.local';
        $legacyDemoUser = User::where('email', $legacyDemoLiderEmail)->first();
        if ($legacyDemoUser) {
            $legacyDemoUser->syncRoles(['lider']);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
