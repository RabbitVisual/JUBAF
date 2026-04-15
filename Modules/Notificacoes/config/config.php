<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações do Módulo de Notificações
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar as opções do módulo de notificações.
    |
    */

    // Habilitar envio de emails
    'email_enabled' => env('NOTIFICACOES_EMAIL_ENABLED', false),

    // Intervalo de polling em milissegundos (quando não usar WebSockets)
    'polling_interval' => env('NOTIFICACOES_POLLING_INTERVAL', 30000),

    // Habilitar broadcasting em tempo real
    'broadcasting_enabled' => env('NOTIFICACOES_BROADCASTING_ENABLED', true),

    // Tipos de notificações disponíveis
    'types' => [
        'info' => [
            'label' => 'Informação',
            'color' => 'blue',
            'icon' => 'information-circle',
        ],
        'success' => [
            'label' => 'Sucesso',
            'color' => 'emerald',
            'icon' => 'check-circle',
        ],
        'warning' => [
            'label' => 'Aviso',
            'color' => 'yellow',
            'icon' => 'exclamation-triangle',
        ],
        'error' => [
            'label' => 'Erro',
            'color' => 'red',
            'icon' => 'x-circle',
        ],
        'alert' => [
            'label' => 'Alerta',
            'color' => 'orange',
            'icon' => 'bell-alert',
        ],
        'system' => [
            'label' => 'Sistema',
            'color' => 'slate',
            'icon' => 'cog-6-tooth',
        ],
    ],

    /*
    | Origem do evento (campo module_source) — chave técnica => etiqueta na UI.
    | Alinhado aos módulos JUBAF (sem referências a projetos antigos).
    */
    'module_sources' => [
        'Core' => 'Núcleo / sistema',
        'Homepage' => 'Site público',
        'PainelJovens' => 'Painel de jovens',
        'PainelLider' => 'Painel de líderes',
        'PainelDiretoria' => 'Diretoria',
        'Avisos' => 'Avisos JUBAF',
        'Blog' => 'Blog',
        'Bible' => 'Bíblia digital',
        'Calendario' => 'Calendário',
        'Chat' => 'Chat',
        'Financeiro' => 'Financeiro',
        'Igrejas' => 'Igrejas',
        'Notificacoes' => 'Notificações',
        'Secretaria' => 'Secretaria',
        'Talentos' => 'Talentos',
    ],
];
