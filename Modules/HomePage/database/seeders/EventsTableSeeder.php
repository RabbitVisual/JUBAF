<?php

namespace Modules\HomePage\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\HomePage\App\Models\Event;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Idempotente: insere apenas o que falta; nunca apaga o que já existe.
     * Só popula quando a tabela events tem o schema do HomePage (title, order, etc.); se for do módulo Events (slug), pula.
     */
    public function run(): void
    {
        // Tabela do módulo Events tem slug; a do HomePage (root migration) tem order/image. Evita conflito.
        if (Schema::hasColumn('events', 'slug')) {
            return;
        }

        $events = [
            [
                'title' => 'Acampamento Estadual',
                'description' => 'Reúne a juventude batista baiana para dias de muita comunhão, louvor, palestras e atividades esportivas.',
                'start_date' => Carbon::now()->next('Friday')->setTime(19, 30, 0),
                'location' => 'Acampamento JUBAF',
                'image' => null,
                'is_active' => true,
                'order' => 1,
                'created_by' => 1,
            ],
            [
                'title' => 'Conferência JUBAF 2026',
                'description' => 'A maior conferência de jovens da associação, com foco em liderança, missões e adoração.',
                'start_date' => Carbon::now()->addDays(30)->setTime(19, 0, 0),
                'location' => 'Auditório Principal',
                'image' => null,
                'is_active' => true,
                'order' => 2,
                'created_by' => 1,
            ],
            [
                'title' => 'Ação Social: Juventude Solidária',
                'description' => 'Projeto que visa alcançar comunidades carentes através de serviços voluntários e arrecadações.',
                'start_date' => Carbon::now()->addDays(15)->setTime(8, 0, 0),
                'location' => 'Comunidade Local',
                'image' => null,
                'is_active' => true,
                'order' => 3,
                'created_by' => 1,
            ],
            [
                'title' => 'Treinamento de Liderança',
                'description' => 'Capacitação intensiva para líderes de juventude das igrejas associadas da região.',
                'start_date' => Carbon::now()->addDays(45)->setTime(14, 0, 0),
                'location' => 'Sede JUBAF',
                'image' => null,
                'is_active' => true,
                'order' => 4,
                'created_by' => 1,
            ],
            [
                'title' => 'Torneio Esportivo',
                'description' => 'Competição amigável entre grupos de jovens em diversas modalidades.',
                'start_date' => Carbon::now()->addDays(60)->setTime(9, 0, 0),
                'end_date' => Carbon::now()->addDays(60)->setTime(18, 0, 0),
                'location' => 'Complexo Esportivo',
                'image' => null,
                'is_active' => true,
                'order' => 5,
                'created_by' => 1,
            ],
        ];

        foreach ($events as $event) {
            Event::firstOrCreate(
                ['title' => $event['title'], 'created_by' => $event['created_by']],
                $event
            );
        }
    }
}
