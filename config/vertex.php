<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações Customizadas do Sistema Vertex
    |--------------------------------------------------------------------------
    |
    | Este arquivo centraliza configurações mapeadas a partir de variáveis de
    | ambiente (.env) para evitar chamadas diretas a env() fora dos arquivos
    | de configuração, permitindo o uso eficiente do 'config:cache'.
    |
    */

    'recaptcha' => [
        'enabled' => env('RECAPTCHA_ENABLED', false),
        'site_key' => env('RECAPTCHA_SITE_KEY', ''),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
        'min_score' => env('RECAPTCHA_MIN_SCORE', 0.5),
    ],

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY', ''),
    ],
];
