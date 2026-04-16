<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('provisioned_by_user_id')
                ->nullable()
                ->after('church_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('provisioned_at')->nullable()->after('provisioned_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['provisioned_by_user_id']);
            $table->dropColumn(['provisioned_by_user_id', 'provisioned_at']);
        });
    }
};
