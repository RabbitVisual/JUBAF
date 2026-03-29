<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('institutional_track', 64)->nullable()->after('slug')
                ->comment('CONJUBAF, congresso_lideres, start_jubaf, encontro_setores, jubaf_na_estrada, outro');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('institutional_track');
        });
    }
};
