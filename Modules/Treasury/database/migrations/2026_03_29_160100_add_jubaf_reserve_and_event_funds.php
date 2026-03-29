<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fundos semânticos para o dashboard executivo (saldo segregado).
     */
    public function up(): void
    {
        if (! Schema::hasTable('financial_funds')) {
            return;
        }

        $now = now();
        foreach ([
            [
                'name' => 'Fundo de Reserva',
                'slug' => 'fundo-reserva',
                'description' => 'Reserva institucional JUBAF / ASBAF',
                'is_restricted' => true,
            ],
            [
                'name' => 'Fundo de Eventos',
                'slug' => 'fundo-eventos',
                'description' => 'Verba alocada a eventos e campanhas associadas',
                'is_restricted' => false,
            ],
        ] as $row) {
            $exists = DB::table('financial_funds')->where('slug', $row['slug'])->exists();
            if (! $exists) {
                DB::table('financial_funds')->insert(array_merge($row, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('financial_funds')) {
            return;
        }
        DB::table('financial_funds')->whereIn('slug', ['fundo-reserva', 'fundo-eventos'])->delete();
    }
};
