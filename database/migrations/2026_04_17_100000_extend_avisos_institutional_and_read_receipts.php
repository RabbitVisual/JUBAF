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
        if (! Schema::hasTable('avisos')) {
            return;
        }

        Schema::table('avisos', function (Blueprint $table) {
            if (! Schema::hasColumn('avisos', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }
            if (! Schema::hasColumn('avisos', 'classificacao')) {
                $table->string('classificacao', 32)->nullable()->after('tipo');
            }
            if (! Schema::hasColumn('avisos', 'target_role')) {
                $table->string('target_role', 64)->nullable()->after('classificacao');
            }
            if (! Schema::hasColumn('avisos', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('data_fim');
            }
            if (! Schema::hasColumn('avisos', 'modo_quadro')) {
                $table->boolean('modo_quadro')->default(false)->after('expires_at');
            }
            if (! Schema::hasColumn('avisos', 'whatsapp_dispatched_at')) {
                $table->timestamp('whatsapp_dispatched_at')->nullable()->after('modo_quadro');
            }
        });

        $driver = Schema::getConnection()->getDriverName();

        $rows = DB::table('avisos')->select('id', 'data_fim')->get();
        foreach ($rows as $row) {
            $uuid = (string) Str::uuid();
            DB::table('avisos')->where('id', $row->id)->update([
                'uuid' => $uuid,
                'expires_at' => $row->data_fim,
            ]);
        }

        Schema::table('avisos', function (Blueprint $table) use ($driver) {
            if ($driver !== 'sqlite') {
                try {
                    $table->unique('uuid');
                } catch (Throwable) {
                    // já indexado
                }
            } else {
                $table->index('uuid');
            }
        });

        if (! Schema::hasTable('aviso_user_read')) {
            Schema::create('aviso_user_read', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aviso_id')->constrained('avisos')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamp('read_at');
                $table->timestamps();

                $table->unique(['aviso_id', 'user_id']);
                $table->index(['user_id', 'read_at']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('aviso_user_read')) {
            Schema::dropIfExists('aviso_user_read');
        }

        if (! Schema::hasTable('avisos')) {
            return;
        }

        Schema::table('avisos', function (Blueprint $table) {
            foreach (['whatsapp_dispatched_at', 'modo_quadro', 'expires_at', 'target_role', 'classificacao', 'uuid'] as $col) {
                if (Schema::hasColumn('avisos', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
