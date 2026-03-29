<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Novas permissões RBAC e matriz role ↔ permission (idempotente).
     */
    public function up(): void
    {
        $newPermissions = [
            ['name' => 'Gerenciar utilizadores', 'slug' => 'gerenciar_usuarios', 'module' => 'Admin', 'description' => 'CRUD de membros e funções no painel admin'],
            ['name' => 'Governança — gestão', 'slug' => 'governance_manage', 'module' => 'Governance', 'description' => 'Assembleias, atas e convocações'],
            ['name' => 'Governança — consulta', 'slug' => 'governance_view', 'module' => 'Governance', 'description' => 'Leitura de assembleias e atas'],
            ['name' => 'Conselho — gestão', 'slug' => 'council_manage', 'module' => 'CoordinationCouncil', 'description' => 'Membros, reuniões e presenças do conselho'],
            ['name' => 'Conselho — consulta', 'slug' => 'council_view', 'module' => 'CoordinationCouncil', 'description' => 'Leitura do conselho de coordenação'],
            ['name' => 'Campo — gestão', 'slug' => 'field_manage', 'module' => 'FieldOutreach', 'description' => 'Visitas e relatórios JUBAF na estrada'],
            ['name' => 'Campo — consulta', 'slug' => 'field_view', 'module' => 'FieldOutreach', 'description' => 'Consulta de visitas'],
            ['name' => 'Notificações em massa', 'slug' => 'notificacoes_broadcast', 'module' => 'Notifications', 'description' => 'Broadcast e templates avançados'],
            ['name' => 'Delegar tesouraria', 'slug' => 'delegar_tesouraria', 'module' => 'Treasury', 'description' => 'Gerir permissões da tesouraria no painel'],
            ['name' => 'Delegar acessos ao painel do membro', 'slug' => 'delegar_acesso_painel', 'module' => 'MemberPanel', 'description' => 'Autorizar módulos no /painel'],
            ['name' => 'Consultar membros', 'slug' => 'ver_membros', 'module' => 'Admin', 'description' => 'Listagem e detalhe de membros'],
        ];

        foreach ($newPermissions as $perm) {
            $exists = DB::table('permissions')->where('slug', $perm['slug'])->exists();
            if (! $exists) {
                DB::table('permissions')->insert(array_merge($perm, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        $slugToId = DB::table('permissions')->pluck('id', 'slug')->all();
        $roleToId = DB::table('roles')->pluck('id', 'slug')->all();

        $matrix = [
            'presidente' => array_keys($slugToId),
            'vice_presidente_1' => array_keys($slugToId),
            'vice_presidente_2' => array_keys($slugToId),
            'secretario_geral' => array_keys($slugToId),
            'secretario_1' => [
                'gerenciar_usuarios', 'gerenciar_igrejas', 'gerenciar_eventos', 'governance_manage', 'governance_view',
                'council_view', 'council_manage', 'field_view', 'field_manage', 'notificacoes_broadcast',
                'delegar_acesso_painel', 'ver_membros', 'acesso_biblia', 'gerenciar_diretoria',
            ],
            'secretario_2' => [
                'gerenciar_usuarios', 'gerenciar_igrejas', 'gerenciar_eventos', 'governance_manage', 'governance_view',
                'council_view', 'field_view', 'notificacoes_broadcast', 'ver_membros', 'acesso_biblia', 'gerenciar_diretoria',
            ],
            'tesoureiro_1' => [
                'gerenciar_financeiro', 'gerenciar_eventos', 'gerenciar_igrejas', 'ver_membros', 'delegar_tesouraria',
                'governance_view', 'council_view', 'field_view', 'acesso_biblia', 'notificacoes_broadcast',
            ],
            'tesoureiro_2' => [
                'gerenciar_financeiro', 'gerenciar_eventos', 'gerenciar_igrejas', 'ver_membros',
                'governance_view', 'council_view', 'field_view', 'acesso_biblia',
            ],
            'lider_jovens' => ['ver_membros', 'gerenciar_eventos', 'acesso_biblia', 'field_view', 'governance_view'],
            'conselheiro' => ['governance_view', 'council_view', 'council_manage', 'field_view', 'ver_membros', 'acesso_biblia'],
        ];

        foreach ($matrix as $roleSlug => $permSlugs) {
            if (! isset($roleToId[$roleSlug])) {
                continue;
            }
            $roleId = $roleToId[$roleSlug];
            foreach ($permSlugs as $pSlug) {
                if (! isset($slugToId[$pSlug])) {
                    continue;
                }
                $permId = $slugToId[$pSlug];
                $exists = DB::table('role_permission')
                    ->where('role_id', $roleId)
                    ->where('permission_id', $permId)
                    ->exists();
                if (! $exists) {
                    DB::table('role_permission')->insert([
                        'role_id' => $roleId,
                        'permission_id' => $permId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        if (isset($roleToId['admin'])) {
            $adminId = $roleToId['admin'];
            foreach ($slugToId as $permId) {
                $exists = DB::table('role_permission')
                    ->where('role_id', $adminId)
                    ->where('permission_id', $permId)
                    ->exists();
                if (! $exists) {
                    DB::table('role_permission')->insert([
                        'role_id' => $adminId,
                        'permission_id' => $permId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        $slugs = [
            'gerenciar_usuarios', 'governance_manage', 'governance_view', 'council_manage', 'council_view',
            'field_manage', 'field_view', 'notificacoes_broadcast', 'delegar_tesouraria', 'delegar_acesso_painel', 'ver_membros',
        ];
        $ids = DB::table('permissions')->whereIn('slug', $slugs)->pluck('id');
        DB::table('role_permission')->whereIn('permission_id', $ids)->delete();
        DB::table('permissions')->whereIn('slug', $slugs)->delete();
    }
};
