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
        if (! Schema::hasTable('igrejas_churches')) {
            return;
        }

        Schema::table('igrejas_churches', function (Blueprint $table) {
            if (! Schema::hasColumn('igrejas_churches', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }
            if (! Schema::hasColumn('igrejas_churches', 'legal_name')) {
                $table->string('legal_name')->nullable()->after('name');
            }
            if (! Schema::hasColumn('igrejas_churches', 'trade_name')) {
                $table->string('trade_name')->nullable()->after('legal_name');
            }
            if (! Schema::hasColumn('igrejas_churches', 'postal_code')) {
                $table->string('postal_code', 16)->nullable()->after('email');
            }
            if (! Schema::hasColumn('igrejas_churches', 'street')) {
                $table->string('street', 255)->nullable()->after('postal_code');
            }
            if (! Schema::hasColumn('igrejas_churches', 'number')) {
                $table->string('number', 32)->nullable()->after('street');
            }
            if (! Schema::hasColumn('igrejas_churches', 'complement')) {
                $table->string('complement', 120)->nullable()->after('number');
            }
            if (! Schema::hasColumn('igrejas_churches', 'district')) {
                $table->string('district', 120)->nullable()->after('complement');
            }
            if (! Schema::hasColumn('igrejas_churches', 'state')) {
                $table->string('state', 8)->nullable()->after('city');
            }
            if (! Schema::hasColumn('igrejas_churches', 'country')) {
                $table->string('country', 8)->nullable()->after('state');
            }
            if (! Schema::hasColumn('igrejas_churches', 'crm_status')) {
                $table->string('crm_status', 32)->default('ativa')->after('is_active');
            }
        });

        if (Schema::hasColumn('igrejas_churches', 'uuid')) {
            DB::table('igrejas_churches')->orderBy('id')->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    $updates = [];
                    if (empty($row->uuid)) {
                        $updates['uuid'] = (string) Str::uuid();
                    }
                    if (Schema::hasColumn('igrejas_churches', 'legal_name') && ($row->legal_name ?? null) === null) {
                        $updates['legal_name'] = $row->name;
                    }
                    if (Schema::hasColumn('igrejas_churches', 'trade_name') && ($row->trade_name ?? null) === null) {
                        $updates['trade_name'] = $row->name;
                    }
                    if (Schema::hasColumn('igrejas_churches', 'crm_status')) {
                        $crm = $row->crm_status ?? null;
                        if ($crm === null || $crm === '') {
                            $updates['crm_status'] = ! empty($row->is_active) ? 'ativa' : 'inativa';
                        }
                    }
                    if (Schema::hasColumn('igrejas_churches', 'country') && ($row->country ?? null) === null) {
                        $updates['country'] = 'BR';
                    }
                    if ($updates !== []) {
                        DB::table('igrejas_churches')->where('id', $row->id)->update($updates);
                    }
                }
            });
        }

        try {
            Schema::table('igrejas_churches', function (Blueprint $table) {
                $table->unique('uuid');
            });
        } catch (Throwable) {
            // index may already exist
        }

        if (Schema::hasTable('jubaf_sectors') && ! Schema::hasColumn('jubaf_sectors', 'scope_kind')) {
            Schema::table('jubaf_sectors', function (Blueprint $table) {
                $table->string('scope_kind', 32)->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('igrejas_churches')) {
            return;
        }

        Schema::table('igrejas_churches', function (Blueprint $table) {
            try {
                $table->dropUnique(['uuid']);
            } catch (Throwable) {
                // ignore
            }
        });

        Schema::table('igrejas_churches', function (Blueprint $table) {
            $drops = [];
            foreach (['uuid', 'legal_name', 'trade_name', 'postal_code', 'street', 'number', 'complement', 'district', 'state', 'country', 'crm_status'] as $col) {
                if (Schema::hasColumn('igrejas_churches', $col)) {
                    $drops[] = $col;
                }
            }
            if ($drops !== []) {
                $table->dropColumn($drops);
            }
        });

        if (Schema::hasTable('jubaf_sectors') && Schema::hasColumn('jubaf_sectors', 'scope_kind')) {
            Schema::table('jubaf_sectors', function (Blueprint $table) {
                $table->dropColumn('scope_kind');
            });
        }
    }
};
