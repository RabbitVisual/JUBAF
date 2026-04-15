<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('talent_profile_skill', function (Blueprint $table) {
            $table->timestamp('validated_at')->nullable()->after('level');
            $table->foreignId('validated_by')->nullable()->after('validated_at')->constrained('users')->nullOnDelete();
            $table->json('links')->nullable()->after('validated_by');
            $table->index(['talent_skill_id', 'validated_at'], 'talent_profile_skill_skill_validated_idx');
        });
    }

    public function down(): void
    {
        Schema::table('talent_profile_skill', function (Blueprint $table) {
            $table->dropIndex('talent_profile_skill_skill_validated_idx');
            $table->dropConstrainedForeignId('validated_by');
            $table->dropColumn(['validated_at', 'links']);
        });
    }
};
