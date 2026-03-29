<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidates: Settings (JUBAF) and CepRanges.
     */
    public function up(): void
    {
        // 1. Settings (JUBAF Focused)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->default('general');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('settings')->insert([
            ['key' => 'site_name', 'value' => 'JUBAF', 'type' => 'string', 'group' => 'general', 'description' => 'Nome da Instituição', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_description', 'value' => 'Juventude Batista Feirense - Unificando a juventude, servindo ao Reino.', 'type' => 'text', 'group' => 'general', 'description' => 'Descrição institucional', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_email', 'value' => 'contato@jubaf.com.br', 'type' => 'string', 'group' => 'general', 'description' => 'E-mail de contato', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_phone', 'value' => '(75) 0000-0000', 'type' => 'string', 'group' => 'general', 'description' => 'Telefone de contato', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_address', 'value' => 'Feira de Santana - BA', 'type' => 'text', 'group' => 'general', 'description' => 'Sede Administrativa', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'logo_path', 'value' => 'storage/image/logo_oficial.png', 'type' => 'file', 'group' => 'appearance', 'description' => 'Logo oficial', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. CEP Ranges
        Schema::create('cep_ranges', function (Blueprint $table) {
            $table->id();
            $table->string('uf', 2)->index();
            $table->string('cidade', 255)->index();
            $table->string('cep_de', 8);
            $table->string('cep_ate', 8);
            $table->string('tipo', 50)->nullable();
            $table->timestamps();
            $table->index(['cep_de', 'cep_ate']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cep_ranges');
        Schema::dropIfExists('settings');
    }
};
