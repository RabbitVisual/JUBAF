<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('events', 'options')) {
            return;
        }

        Schema::table('events', function (Blueprint $table) {
            $table->json('options')->nullable()->after('ticket_template_id');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
};
