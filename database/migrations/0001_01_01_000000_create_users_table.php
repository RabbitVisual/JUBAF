<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Identificação
            $table->id();
            $table->string('name'); // Nome completo (para compatibilidade com Laravel Auth)
            $table->string('first_name', 100)->nullable(); // Nome
            $table->string('last_name', 100)->nullable(); // Sobrenome
            $table->string('cpf', 14)->unique()->nullable(); // CPF
            $table->date('date_of_birth')->nullable(); // Data de nascimento
            $table->enum('gender', ['M', 'F'])->nullable(); // Sexo: Masculino, Feminino
            $table->enum('marital_status', ['solteiro', 'casado', 'divorciado', 'viuvo', 'uniao_estavel'])->nullable(); // Estado civil

            // Contato
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable(); // Telefone fixo
            $table->string('cellphone', 20)->nullable(); // Celular
            $table->timestamp('email_verified_at')->nullable();

            // Endereço (Essencial para logística de eventos da federação)
            $table->string('address', 255)->nullable();
            $table->string('address_number', 20)->nullable();
            $table->string('address_complement', 100)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('zip_code', 10)->nullable();

            // Sistema JUBAF
            $table->string('password');
            $table->unsignedBigInteger('role_id')->default(10); // Default: Membro (ID 10 na unificação)
            $table->unsignedBigInteger('church_id')->nullable()->comment('ID da igreja federada');
            $table->boolean('is_active')->default(true);
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            
            // Segurança
            $table->text('two_factor_secret')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
