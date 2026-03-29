<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('churches', function (Blueprint $table) {
            $table->foreignId('unijovem_representative_user_id')->nullable()->after('leader_phone')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('churches', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unijovem_representative_user_id');
        });
    }
};
