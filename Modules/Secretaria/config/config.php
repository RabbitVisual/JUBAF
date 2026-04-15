<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Integrações (Notificacoes, Calendario, Avisos, Homepage)
    |--------------------------------------------------------------------------
    */
    'integrations' => [
        'notificacoes_on_publish' => true,
        'notificacoes_filter_lider_by_church' => true,
        'calendario_sync_meetings' => false,
        'calendario_meeting_visibility' => 'diretoria',
        'calendario_delete_event_on_meeting_delete' => true,
        'aviso_draft_on_minute_publish' => false,
        'aviso_draft_on_convocation_publish' => false,
        'homepage_public_secretaria_cta' => true,
    ],
    'required_minute_signers' => ['presidente', 'secretario-1'],
];
