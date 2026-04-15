<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('fin_obligations')) {
            Schema::create('fin_obligations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('church_id')->constrained('igrejas_churches')->cascadeOnDelete();
                $table->unsignedSmallInteger('assoc_start_year')->comment('Ano de início do ciclo associativo (mar.–fev.)');
                $table->decimal('amount', 15, 2);
                $table->string('currency', 8)->default('BRL');
                $table->string('status', 24)->default('pending')->index();
                $table->foreignId('fin_transaction_id')->nullable()->constrained('fin_transactions')->nullOnDelete();
                $table->timestamp('generated_at')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->string('notes')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->unique(['church_id', 'assoc_start_year'], 'fin_obligations_church_year_unique');
            });
        }

        if (Schema::hasTable('fin_transactions') && ! Schema::hasColumn('fin_transactions', 'secretaria_minute_id')) {
            Schema::table('fin_transactions', function (Blueprint $table) {
                $table->foreignId('secretaria_minute_id')
                    ->nullable()
                    ->after('metadata')
                    ->constrained('secretaria_minutes')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('fin_transactions') && Schema::hasColumn('fin_transactions', 'secretaria_minute_id')) {
            Schema::table('fin_transactions', function (Blueprint $table) {
                $table->dropConstrainedForeignId('secretaria_minute_id');
            });
        }

        Schema::dropIfExists('fin_obligations');
    }
};
