<?php

namespace Modules\Igrejas\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\Igrejas\App\Models\JubafSector;

class IgrejasDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::hasTable('jubaf_sectors')) {
            return;
        }

        $defaults = [
            ['name' => 'Setor 1 — Vice-presidência 1', 'sort_order' => 10],
            ['name' => 'Setor 2 — Vice-presidência 2', 'sort_order' => 20],
        ];

        foreach ($defaults as $row) {
            $sector = JubafSector::query()->firstOrCreate(
                ['name' => $row['name']],
                [
                    'slug' => JubafSector::uniqueSlugFromName($row['name']),
                    'description' => 'Setor associacional JUBAF (ajuste os nomes à realidade local).',
                    'sort_order' => $row['sort_order'],
                    'is_active' => true,
                ]
            );
            if (empty($sector->slug)) {
                $sector->update(['slug' => JubafSector::uniqueSlugFromName($sector->name)]);
            }
        }
    }
}
