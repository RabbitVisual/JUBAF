<?php

return [
    'name' => 'Financeiro',

    'quota' => [
        'default_amount' => (float) env('JUBAF_ASSOC_QUOTA_AMOUNT', 100),
        'income_category_code' => env('JUBAF_QUOTA_FIN_CATEGORY_CODE', ''),
        /** Valor por mês em fin_quota_invoices (cotas associativas mensais). */
        'monthly_invoice_amount' => (float) env('JUBAF_ASSOC_MONTHLY_QUOTA_AMOUNT', 0) ?: (float) env('JUBAF_ASSOC_QUOTA_AMOUNT', 100),
    ],

    /** Grupos do plano de contas que exigem ata + comprovante para despesas (além do flag na categoria). */
    'extraordinary_expense_group_keys' => array_values(array_filter(array_map('trim', explode(',', (string) env(
        'JUBAF_FIN_EXTRAORDINARY_GROUPS',
        'despesas_administrativas'
    ))))),
];
