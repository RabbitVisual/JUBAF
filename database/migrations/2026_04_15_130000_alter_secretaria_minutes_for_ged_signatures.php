<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('secretaria_minutes')) {
            return;
        }

        Schema::table('secretaria_minutes', function (Blueprint $table) {
            if (! Schema::hasColumn('secretaria_minutes', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }
            if (! Schema::hasColumn('secretaria_minutes', 'content')) {
                $table->longText('content')->nullable()->after('body');
            }
            if (! Schema::hasColumn('secretaria_minutes', 'meeting_date')) {
                $table->date('meeting_date')->nullable()->after('meeting_id');
            }
            if (! Schema::hasColumn('secretaria_minutes', 'document_hash')) {
                $table->string('document_hash', 64)->nullable()->after('content_checksum');
            }
            if (! Schema::hasColumn('secretaria_minutes', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('document_hash');
            }
        });

        DB::table('secretaria_minutes')
            ->whereNull('uuid')
            ->orderBy('id')
            ->lazyById()
            ->each(function ($row): void {
                DB::table('secretaria_minutes')
                    ->where('id', $row->id)
                    ->update(['uuid' => (string) Str::uuid()]);
            });

        if (Schema::hasColumn('secretaria_minutes', 'body')) {
            DB::statement('UPDATE secretaria_minutes SET content = body WHERE content IS NULL AND body IS NOT NULL');
        }

        if (Schema::hasTable('secretaria_meetings')) {
            DB::statement(
                'UPDATE secretaria_minutes sm
                 JOIN secretaria_meetings mt ON mt.id = sm.meeting_id
                 SET sm.meeting_date = DATE(mt.starts_at)
                 WHERE sm.meeting_id IS NOT NULL
                   AND sm.meeting_date IS NULL
                   AND mt.starts_at IS NOT NULL'
            );
        }

        DB::statement(
            "UPDATE secretaria_minutes
             SET status = 'pending_signatures'
             WHERE status IN ('pending_approval', 'approved')"
        );

        DB::statement(
            "UPDATE secretaria_minutes
             SET status = 'draft'
             WHERE status NOT IN ('draft', 'pending_signatures', 'published', 'archived')"
        );

        Schema::table('secretaria_minutes', function (Blueprint $table) {
            if (Schema::hasColumn('secretaria_minutes', 'uuid')) {
                $table->unique('uuid');
            }
            if (Schema::hasColumn('secretaria_minutes', 'meeting_date')) {
                $table->index('meeting_date');
            }
            if (Schema::hasColumn('secretaria_minutes', 'status')) {
                $table->index('status');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('secretaria_minutes')) {
            return;
        }

        Schema::table('secretaria_minutes', function (Blueprint $table) {
            try {
                $table->dropUnique('secretaria_minutes_uuid_unique');
            } catch (\Throwable $e) {
            }
            try {
                $table->dropIndex('secretaria_minutes_meeting_date_index');
            } catch (\Throwable $e) {
            }
            try {
                $table->dropIndex('secretaria_minutes_status_index');
            } catch (\Throwable $e) {
            }

            if (Schema::hasColumn('secretaria_minutes', 'pdf_path')) {
                $table->dropColumn('pdf_path');
            }
            if (Schema::hasColumn('secretaria_minutes', 'document_hash')) {
                $table->dropColumn('document_hash');
            }
            if (Schema::hasColumn('secretaria_minutes', 'meeting_date')) {
                $table->dropColumn('meeting_date');
            }
            if (Schema::hasColumn('secretaria_minutes', 'content')) {
                $table->dropColumn('content');
            }
            if (Schema::hasColumn('secretaria_minutes', 'uuid')) {
                $table->dropColumn('uuid');
            }
        });
    }
};
