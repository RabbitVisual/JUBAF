<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('jubaf_sectors')) {
            Schema::create('jubaf_sectors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('description')->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('igrejas_churches') && ! Schema::hasColumn('igrejas_churches', 'jubaf_sector_id')) {
            Schema::table('igrejas_churches', function (Blueprint $table) {
                $table->foreignId('jubaf_sector_id')
                    ->nullable()
                    ->after('sector')
                    ->constrained('jubaf_sectors')
                    ->nullOnDelete();
            });
        }

        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'jubaf_sector_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('jubaf_sector_id')
                    ->nullable()
                    ->after('church_id')
                    ->constrained('jubaf_sectors')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'jubaf_sector_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('jubaf_sector_id');
            });
        }

        if (Schema::hasTable('igrejas_churches') && Schema::hasColumn('igrejas_churches', 'jubaf_sector_id')) {
            Schema::table('igrejas_churches', function (Blueprint $table) {
                $table->dropConstrainedForeignId('jubaf_sector_id');
            });
        }

        Schema::dropIfExists('jubaf_sectors');
    }
};
