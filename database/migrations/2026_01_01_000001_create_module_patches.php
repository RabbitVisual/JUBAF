<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidates all cumulative module patches into thematic tables if they already exist,
     * or adds columns if the tables were created in base migrations.
     * This avoids multiple "add_column" files.
     */
    public function up(): void
    {
        // Note: These columns are intended for existing module tables. 
        // In a core consolidation, some might be better in the base tables, 
        // but for safety in a modular monolith, we keep them here as a unified patch file.

        // 1. Bible Module Patches
        if (Schema::hasTable('bible_favorites')) {
            Schema::table('bible_favorites', function (Blueprint $table) {
                if (!Schema::hasColumn('bible_favorites', 'color')) {
                    $table->string('color', 20)->nullable();
                }
                if (!Schema::hasColumn('bible_favorites', 'note')) {
                    $table->text('note')->nullable();
                }
            });
        }

        if (Schema::hasTable('bible_plans')) {
            Schema::table('bible_plans', function (Blueprint $table) {
                if (!Schema::hasColumn('bible_plans', 'reading_mode')) {
                    $table->string('reading_mode')->default('sequential');
                }
                if (!Schema::hasColumn('bible_plans', 'allow_back_tracking')) {
                    $table->boolean('allow_back_tracking')->default(true);
                }
            });
        }

        // 2. Treasury Module Patches
        if (Schema::hasTable('financial_goals')) {
            Schema::table('financial_goals', function (Blueprint $table) {
                if (!Schema::hasColumn('financial_goals', 'icon')) {
                    $table->string('icon')->nullable();
                }
                if (!Schema::hasColumn('financial_goals', 'color')) {
                    $table->string('color')->nullable();
                }
            });
        }

        if (Schema::hasTable('financial_entries')) {
            Schema::table('financial_entries', function (Blueprint $table) {
                if (!Schema::hasColumn('financial_entries', 'goal_id')) {
                    $table->foreignId('goal_id')->nullable()->constrained('financial_goals')->nullOnDelete();
                }
            });
        }

        // 3. System Notifications Patches
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                // Adjusting fields if not following standard Laravel structure
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversing patches here is complex, but standard for Laraval.
    }
};
