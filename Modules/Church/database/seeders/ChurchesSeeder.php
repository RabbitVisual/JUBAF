<?php

namespace Modules\Church\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Church\Models\Church;
use Illuminate\Support\Str;

class ChurchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $churches = [
            // Exemplo de lista representativa (O usuário poderá editar conforme a realidade local)
            ['name' => 'Igreja Batista Central de Feira', 'sector' => 'Setor 1', 'unijovem_name' => 'Unijovem Central'],
            ['name' => 'Igreja Batista Sião', 'sector' => 'Setor 1', 'unijovem_name' => 'Jovens Sião'],
            ['name' => 'Igreja Batista Betel', 'sector' => 'Setor 2', 'unijovem_name' => 'Radicais Betel'],
            ['name' => 'Primeira Igreja Batista de Feira', 'sector' => 'Setor 1', 'unijovem_name' => 'Geração Eleita'],
            ['name' => 'Igreja Batista Alvorada', 'sector' => 'Setor 3', 'unijovem_name' => 'Alvorada Teen/Jovem'],
            ['name' => 'Igreja Batista Ebenézer', 'sector' => 'Setor 4', 'unijovem_name' => 'Ebenézer Youth'],
            ['name' => 'Igreja Batista Boas Novas', 'sector' => 'Setor 2', 'unijovem_name' => 'BN Jovens'],
        ];

        foreach ($churches as $church) {
            Church::updateOrCreate(
                ['slug' => Str::slug($church['name'])],
                array_merge($church, [
                    'city' => 'Feira de Santana',
                    'is_active' => true
                ])
            );
        }
        
        // Adicionar placeholders até completar 70 se necessário, 
        // ou deixar o usuário preencher o restante via painel.
    }
}
