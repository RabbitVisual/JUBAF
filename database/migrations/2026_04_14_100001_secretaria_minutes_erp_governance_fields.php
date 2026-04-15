<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('secretaria_minutes')) {
            return;
        }

        Schema::table('secretaria_minutes', function (Blueprint $table) {
            if (! Schema::hasColumn('secretaria_minutes', 'protocol_number')) {
                $table->string('protocol_number')->nullable()->unique()->after('title');
            }
            if (! Schema::hasColumn('secretaria_minutes', 'executive_summary')) {
                $table->text('executive_summary')->nullable()->after('body');
            }
            if (! Schema::hasColumn('secretaria_minutes', 'content_checksum')) {
                $table->string('content_checksum', 64)->nullable()->after('published_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('secretaria_minutes')) {
            return;
        }

        Schema::table('secretaria_minutes', function (Blueprint $table) {
            if (Schema::hasColumn('secretaria_minutes', 'content_checksum')) {
                $table->dropColumn('content_checksum');
            }
            if (Schema::hasColumn('secretaria_minutes', 'executive_summary')) {
                $table->dropColumn('executive_summary');
            }
            if (Schema::hasColumn('secretaria_minutes', 'protocol_number')) {
                $table->dropColumn('protocol_number');
            }
        });
    }
};
