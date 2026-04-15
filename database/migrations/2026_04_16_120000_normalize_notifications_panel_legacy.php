<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('notifications')) {
            return;
        }

        DB::table('notifications')
            ->where('panel', 'co-admin')
            ->update(['panel' => 'diretoria']);
    }

    public function down(): void
    {
        // Não reverter para co-admin (legado removido)
    }
};
