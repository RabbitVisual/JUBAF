<?php

namespace Modules\Events\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Events\App\Models\EventType;

class EventTypesSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            // Major JUBAF Events
            ['name' => 'CONJUBAF (Congresso Geral)',  'slug' => 'conjubaf',         'icon' => 'campground',       'color' => '#1D4ED8', 'order' => 1],
            ['name' => 'START JUBAF',                 'slug' => 'start-jubaf',      'icon' => 'rocket',           'color' => '#EA580C', 'order' => 2],
            ['name' => 'Congresso de Líderes',        'slug' => ' líderes',         'icon' => 'users-gear',       'color' => '#0D9488', 'order' => 3],
            ['name' => 'JUBAF na Estrada',            'slug' => 'jubaf-estrada',    'icon' => 'bus',              'color' => '#2563EB', 'order' => 4],
            ['name' => 'Encontro dos Setores',         'slug' => 'encontro-setores', 'icon' => 'layer-group',      'color' => '#7C3AED', 'order' => 5],
            
            // Other Categories
            ['name' => 'Treinamento / Capacitação',   'slug' => 'treinamento',       'icon' => 'chalkboard-user',  'color' => '#D97706', 'order' => 6],
            ['name' => 'Visita Institucional',         'slug' => 'visita',            'icon' => 'church',           'color' => '#059669', 'order' => 7],
            ['name' => 'Ação Social / Missionária',   'slug' => 'acao-social',       'icon' => 'hands-holding-heart','color' => '#B45309', 'order' => 8],
            ['name' => 'Evento Esportivo / Lazer',     'slug' => 'esporte-lazer',     'icon' => 'volleyball',       'color' => '#0EA5E9', 'order' => 9],
            ['name' => 'Outro',                        'slug' => 'outro',             'icon' => 'calendar',         'color' => '#6B7280', 'order' => 99],
        ];

        foreach ($types as $type) {
            EventType::updateOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }
    }
}
