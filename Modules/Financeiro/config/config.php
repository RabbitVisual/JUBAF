<?php

return [
    'name' => 'Financeiro',

    'quota' => [
        'default_amount' => (float) env('JUBAF_ASSOC_QUOTA_AMOUNT', 100),
        'income_category_code' => env('JUBAF_QUOTA_FIN_CATEGORY_CODE', ''),
    ],
];
