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
        Schema::create('churches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('unijovem_name')->nullable();
            $table->string('sector')->nullable(); // Setor 1, Setor 2, etc.

            // Liderança
            $table->string('leader_name')->nullable();
            $table->string('leader_phone')->nullable();

            // Localização
            $table->string('city')->default('Feira de Santana');
            $table->string('neighborhood')->nullable();
            $table->text('address')->nullable();

            // Status e Mídia
            $table->boolean('is_active')->default(true);
            $table->string('logo_path')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('churches');
    }
};
