<?php

namespace Modules\Homepage\Database\Seeders;

use App\Models\SystemConfig;
use Illuminate\Database\Seeder;
use Modules\Homepage\App\Support\HomepageDefaults;

class HomepageDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $set = function (string $key, mixed $value, string $type = 'string', string $group = 'homepage', ?string $description = null): void {
            if (SystemConfig::where('key', $key)->exists()) {
                return;
            }
            SystemConfig::set($key, $value, $type, $group, $description ?? 'Default homepage');
        };

        $set('homepage_hero_badge', 'JUBAF · Tema 2026: SOMOS UM');
        $set('homepage_hero_title', 'Juventude Batista Feirense');
        $set('homepage_hero_subtitle', 'Somos um só corpo em Cristo. Caminhamos juntos na fé, na amizade e no serviço — tema deste ano: SOMOS UM.');
        $set('homepage_hero_enabled', true, 'boolean');
        $set('homepage_servicos_enabled', true, 'boolean');
        $set('homepage_sobre_enabled', true, 'boolean');
        $set('homepage_servicos_publicos_enabled', true, 'boolean');
        $set('homepage_contato_enabled', true, 'boolean');

        $set('homepage_servicos_section_title', 'O que vivemos juntos');
        $set('homepage_servicos_section_subtitle', 'Encontros, discipulado, eventos e comunicação — a JUBAF em movimento.');

        $set('homepage_sobre_badge', 'Sobre nós');
        $set('homepage_sobre_title', 'Juventude Batista Feirense');
        $set('homepage_sobre_mission', 'Proclamar Jesus, formar discípulos e servir nossa cidade com amor e verdade.');
        $set('homepage_sobre_vision', 'Ser referência de juventude batista engajada no Reino de Deus.');
        $set('homepage_sobre_values', 'Fé bíblica, comunhão, missão e transparência.');
        $set('homepage_sobre_p1', 'A JUBAF reúne jovens das igrejas batistas em Feira de Santana e região para celebrar, aprender e enviar — em unidade com a ASBAF e com o corpo de Cristo.');
        $set('homepage_sobre_p2', 'Crescemos em grupos, eventos e projetos que fortalecem a identidade batista e o chamado missionário de cada jovem.');
        $set('homepage_sobre_stat1_value', '70+');
        $set('homepage_sobre_stat1_label', 'Igrejas na ASBAF');
        $set('homepage_sobre_stat2_value', '1');
        $set('homepage_sobre_stat2_label', 'Só corpo em Cristo');

        $set('homepage_navbar_inicio_enabled', true, 'boolean');
        $set('homepage_navbar_servicos_enabled', true, 'boolean');
        $set('homepage_navbar_sobre_enabled', true, 'boolean');
        $set('homepage_navbar_consulta_enabled', true, 'boolean');
        $set('homepage_navbar_contato_enabled', true, 'boolean');

        $set('homepage_footer_descricao', 'Juventude Batista Feirense — caminhando juntos no tema SOMOS UM.');
        $set('homepage_footer_external_link_label', 'Site institucional');
        $set('homepage_footer_org_line', 'JUBAF — Juventude Batista Feirense');

        $set('homepage_footer_credit_visible', false, 'boolean');
        $set('homepage_footer_credit_organization', '');
        $set('homepage_footer_credit_contact_name', '');
        $set('homepage_footer_credit_email', '');
        $set('homepage_footer_credit_phone', '');

        if (! SystemConfig::where('key', 'homepage_servicos_cards')->exists()) {
            SystemConfig::set(
                'homepage_servicos_cards',
                HomepageDefaults::servicosCards(),
                'json',
                'homepage',
                'Cartões da secção de atividades na homepage'
            );
        }

        if (! SystemConfig::where('key', 'carousel_enabled')->exists()) {
            SystemConfig::set('carousel_enabled', true, 'boolean', 'carousel', 'Habilita carrossel na homepage');
        }
    }
}
