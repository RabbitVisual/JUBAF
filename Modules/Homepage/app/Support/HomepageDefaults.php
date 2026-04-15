<?php

namespace Modules\Homepage\App\Support;

class HomepageDefaults
{
    public static function servicosCards(): array
    {
        return [
            [
                'title' => 'SOMOS UM',
                'description' => 'Somos a Juventude Batista Feirense: um só corpo, muitas histórias, uma fé que nos move a servir, acolher e crescer juntos em Cristo.',
                'bullets' => [
                    'Encontros que fortalecem a comunhão',
                    'Espaço seguro para dúvidas e amizades',
                    'Missão local com identidade batista',
                ],
                'link' => '',
                'link_text' => '',
                'accent' => 'blue',
                'icon' => 'heart',
            ],
            [
                'title' => 'Juventude & discipulado',
                'description' => 'Caminhamos juntos na Palavra, na oração e na vida em grupo — formando líderes e amigos para o Reino.',
                'bullets' => [
                    'Estudos e momentos de adoração',
                    'Mentoria e acompanhamento espiritual',
                    'Integração com igrejas e setores',
                ],
                'link' => '',
                'link_text' => '',
                'accent' => 'indigo',
                'icon' => 'book-open',
            ],
            [
                'title' => 'Eventos e congressos',
                'description' => 'Vivencie CONJUBAF, encontros de setor, retiros e celebrações que marcam nossa caminhada.',
                'bullets' => [
                    'Programação ao longo do ano',
                    'Inscrições e comunicados oficiais',
                    'Momentos inesquecíveis de unidade',
                ],
                'link' => '',
                'link_text' => '',
                'accent' => 'violet',
                'icon' => 'calendar-days',
            ],
            [
                'title' => 'Comunicação e comunidade',
                'description' => 'Fique por dentro de notícias, campanhas e oportunidades de servir na JUBAF.',
                'bullets' => [
                    'Redes sociais e avisos importantes',
                    'Voluntariado e projetos locais',
                    'Transparência com a família batista',
                ],
                'link' => '',
                'link_text' => '',
                'accent' => 'cyan',
                'icon' => 'bullhorn',
            ],
        ];
    }

    public static function servicosCardsJson(): string
    {
        return json_encode(self::servicosCards(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
