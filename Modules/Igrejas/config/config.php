<?php

return [
    'name' => 'Igrejas',

    'integrations' => [
        'notify_directorate_on_request_submit' => env('IGREJAS_NOTIFY_REQUEST_SUBMIT', true),
        'notify_submitter_on_request_resolve' => env('IGREJAS_NOTIFY_REQUEST_RESOLVE', true),
        'aviso_draft_on_church_activated' => env('IGREJAS_AVISO_DRAFT_CHURCH', false),
        'calendario_warn_local_overlap' => env('IGREJAS_CAL_WARN_OVERLAP', true),
    ],
];
