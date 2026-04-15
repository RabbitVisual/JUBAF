<?php

namespace Modules\Financeiro\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Igrejas\App\Models\Church;

class FinObligation extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_WAIVED = 'waived';

    public const STATUS_CANCELLED = 'cancelled';

    protected $table = 'fin_obligations';

    protected $fillable = [
        'church_id',
        'assoc_start_year',
        'amount',
        'currency',
        'status',
        'fin_transaction_id',
        'generated_at',
        'paid_at',
        'notes',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'generated_at' => 'datetime',
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
