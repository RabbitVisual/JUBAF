<?php

namespace Modules\HomePage\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\HomePage\App\Models\GalleryImage;

class GalleryImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Idempotente: insere apenas o que falta; nunca apaga o que já existe.
        $images = [
            [
                'title' => 'Assembleia Geral',
                'description' => 'Momento de deliberação e comunhão da juventude',
                'image_path' => 'gallery/sample1.jpg',
                'image_url' => null,
                'category' => 'eventos',
                'is_active' => true,
                'order' => 1,
                'created_by' => 1,
            ],
            [
                'title' => 'Encontro de Líderes',
                'description' => 'Capacitação e planejamento estratégico',
                'image_path' => 'gallery/sample2.jpg',
                'image_url' => null,
                'category' => 'eventos',
                'is_active' => true,
                'order' => 2,
                'created_by' => 1,
            ],
            [
                'title' => 'Projeto Social',
                'description' => 'Ação solidária da juventude na comunidade',
                'image_path' => 'gallery/sample3.jpg',
                'image_url' => null,
                'category' => 'projetos',
                'is_active' => true,
                'order' => 3,
                'created_by' => 1,
            ],
            [
                'title' => 'Congresso de Jovens',
                'description' => 'Celebração, palavra e adoração',
                'image_path' => 'gallery/sample4.jpg',
                'image_url' => null,
                'category' => 'eventos',
                'is_active' => true,
                'order' => 4,
                'created_by' => 1,
            ],
            [
                'title' => 'Esportes JUBAF',
                'description' => 'Integração através de competições saudáveis',
                'image_path' => 'gallery/sample5.jpg',
                'image_url' => null,
                'category' => 'esportes',
                'is_active' => true,
                'order' => 5,
                'created_by' => 1,
            ],
            [
                'title' => 'Retiro Espiritual',
                'description' => 'Dias de imersão e busca ao Senhor',
                'image_path' => 'gallery/sample6.jpg',
                'image_url' => null,
                'category' => 'eventos',
                'is_active' => true,
                'order' => 6,
                'created_by' => 1,
            ],
            [
                'title' => 'Louvor',
                'description' => 'Adoração através da música na JUBAF',
                'image_path' => 'gallery/sample7.jpg',
                'image_url' => null,
                'category' => 'arte',
                'is_active' => true,
                'order' => 7,
                'created_by' => 1,
            ],
            [
                'title' => 'Comunhão',
                'description' => 'Fé, amor e unidade entre os jovens',
                'image_path' => 'gallery/sample8.jpg',
                'image_url' => null,
                'category' => 'comunidade',
                'is_active' => true,
                'order' => 8,
                'created_by' => 1,
            ],
        ];

        foreach ($images as $img) {
            GalleryImage::firstOrCreate(
                ['title' => $img['title'], 'category' => $img['category'], 'created_by' => $img['created_by']],
                $img
            );
        }
    }
}
