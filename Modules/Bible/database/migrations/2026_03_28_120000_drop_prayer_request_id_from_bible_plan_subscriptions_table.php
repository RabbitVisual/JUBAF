<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove legacy nullable prayer_request_id from bible plan subscriptions (JUBAF2026).
     *
     * Em bases antigas a coluna pode existir sem FK (quando não havia tabela prayer_requests);
     * o dropForeign por convenção falha nesse caso — por isso o FK é removido só se existir.
     */
    public function up(): void
    {
        if (! Schema::hasTable('bible_plan_subscriptions')) {
            return;
        }
        if (! Schema::hasColumn('bible_plan_subscriptions', 'prayer_request_id')) {
            return;
        }

        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'mysql') {
            $database = $connection->getDatabaseName();
            $row = $connection->selectOne(
                'select CONSTRAINT_NAME as name from information_schema.KEY_COLUMN_USAGE
                 where TABLE_SCHEMA = ? and TABLE_NAME = ? and COLUMN_NAME = ?
                 and REFERENCED_TABLE_NAME is not null
                 limit 1',
                [$database, 'bible_plan_subscriptions', 'prayer_request_id']
            );
            if ($row && ! empty($row->name)) {
                $name = str_replace('`', '``', $row->name);
                DB::statement('ALTER TABLE `bible_plan_subscriptions` DROP FOREIGN KEY `'.$name.'`');
            }
        } else {
            try {
                Schema::table('bible_plan_subscriptions', function (Blueprint $table) {
                    $table->dropForeign(['prayer_request_id']);
                });
            } catch (\Throwable) {
                // Coluna sem FK (ex.: SQLite / coluna bigint sem constraint).
            }
        }

        Schema::table('bible_plan_subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('bible_plan_subscriptions', 'prayer_request_id')) {
                $table->dropColumn('prayer_request_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('bible_plan_subscriptions')) {
            return;
        }
        Schema::table('bible_plan_subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('bible_plan_subscriptions', 'prayer_request_id')) {
                return;
            }
            $table->unsignedBigInteger('prayer_request_id')->nullable()->after('projected_end_date');
        });
    }
};
