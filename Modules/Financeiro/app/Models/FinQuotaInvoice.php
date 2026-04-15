<?php

namespace Modules\Financeiro\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Igrejas\App\Models\Church;

class FinQuotaInvoice extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_WAIVED = 'waived';

    public const STATUS_CANCELLED = 'cancelled';

    protected $table = 'fin_quota_invoices';

    protected $fillable = [
        'church_id',
        'billing_month',
        'amount',
        'currency',
        'status',
        'fin_transaction_id',
        'due_on',
        'paid_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_on' => 'date',
            'paid_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    public function finTransaction(): BelongsTo
    {
        return $this->belongsTo(FinTransaction::class, 'fin_transaction_id');
    }
}
