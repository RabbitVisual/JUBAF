<?php

return [
    'name' => 'Homepage',

    /** TTL (segundos) para cache dos blocos dinâmicos do portal (igrejas, eventos). */
    'portal_cache_ttl' => (int) env('HOMEPAGE_PORTAL_CACHE_TTL', 600),
];
