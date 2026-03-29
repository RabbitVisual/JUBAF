<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidates: Roles, Permissions, and relationship.
     * Aligned with JUBAF Statute (Art. 7, 16, 25).
     */
    public function up(): void
    {
        // 1. Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // JUBAF Statutory Roles
        $roles = [
            ['name' => 'Administrador', 'slug' => 'admin', 'description' => 'Acesso total ao sistema'],
            ['name' => 'Presidente', 'slug' => 'presidente', 'description' => 'Presidente da JUBAF (Art. 7)'],
            ['name' => '1º Vice-presidente', 'slug' => 'vice_presidente_1', 'description' => '1º Vice-presidente (Art. 7)'],
            ['name' => '2º Vice-presidente', 'slug' => 'vice_presidente_2', 'description' => '2º Vice-presidente (Art. 7)'],
            ['name' => '1º Secretário', 'slug' => 'secretario_1', 'description' => '1º Secretário (Art. 7)'],
            ['name' => '2º Secretário', 'slug' => 'secretario_2', 'description' => '2º Secretário (Art. 7)'],
            ['name' => '1º Tesoureiro', 'slug' => 'tesoureiro_1', 'description' => '1º Tesoureiro (Art. 7)'],
            ['name' => '2º Tesoureiro', 'slug' => 'tesoureiro_2', 'description' => '2º Tesoureiro (Art. 7)'],
            ['name' => 'Secretário Geral', 'slug' => 'secretario_geral', 'description' => 'Oficial Executivo da JUBAF (Art. 25)'],
            ['name' => 'Líder de jovens', 'slug' => 'lider_jovens', 'description' => 'Representante de Igreja/UNIJOVEM (Art. 16c)'],
            ['name' => 'Conselheiro', 'slug' => 'conselheiro', 'description' => 'Membro Efetivo do Conselho (Art. 16b)'],
            ['name' => 'Membro', 'slug' => 'membro', 'description' => 'Jovem Congressista/Filiado'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert(array_merge($role, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 2. Permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('module')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        $permissions = [
            ['name' => 'Gerenciar Igrejas', 'slug' => 'gerenciar_igrejas', 'module' => 'Federacao', 'description' => 'Gestão das 70 igrejas da ASBAF'],
            ['name' => 'Gerenciar Eventos', 'slug' => 'gerenciar_eventos', 'module' => 'Eventos', 'description' => 'Gestão do CONJUBAF e conclaves'],
            ['name' => 'Gerenciar Financeiro', 'slug' => 'gerenciar_financeiro', 'module' => 'Tesouraria', 'description' => 'Gestão das verbas da ASBAF e ofertas'],
            ['name' => 'Gerenciar Diretoria', 'slug' => 'gerenciar_diretoria', 'module' => 'Admin', 'description' => 'Mapeamento de mandatos e conselho'],
            ['name' => 'Acesso Bíblia', 'slug' => 'acesso_biblia', 'module' => 'Biblia', 'description' => 'Leitura e marcadores de versículos'],
        ];

        foreach ($permissions as $perm) {
            DB::table('permissions')->insert(array_merge($perm, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 3. Role Permission Relationship
        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['role_id', 'permission_id']);
        });

        // Default Admin Permissions
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');
        if ($adminRoleId) {
            $permissionIds = DB::table('permissions')->pluck('id');
            foreach ($permissionIds as $pId) {
                DB::table('role_permission')->insert([
                    'role_id' => $adminRoleId,
                    'permission_id' => $pId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
