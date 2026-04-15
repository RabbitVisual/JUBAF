<?php

namespace Modules\Talentos\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Talentos\App\Models\TalentArea;
use Modules\Talentos\App\Models\TalentSkill;

class TalentosDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'Canto',
            'Instrumento — teclado',
            'Instrumento — violão',
            'Som e áudio',
            'Projeção / multimédia',
            'Fotografia',
            'Vídeo',
            'Recepção',
            'Coordenação de equipe',
            'Evangelismo',
        ];

        foreach ($skills as $name) {
            TalentSkill::query()->firstOrCreate(['name' => $name]);
        }

        $areas = [
            'Culto e adoração',
            'Comunicação e mídia',
            'Eventos e logística',
            'Ação social',
            'Ensino e discipulado',
            'Jovens e recreação',
        ];

        foreach ($areas as $name) {
            TalentArea::query()->firstOrCreate(['name' => $name]);
        }
    }
}
