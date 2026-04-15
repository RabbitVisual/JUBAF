<?php

namespace Modules\Financeiro\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinExpenseRequest extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_PAID = 'paid';

    protected $table = 'fin_expense_requests';

    protected $fillable = [
        'amount',
        'justification',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
        'paid_transaction_id',
        'attachment_path',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function paidTransaction(): BelongsTo
    {
        return $this->belongsTo(FinTransaction::class, 'paid_transaction_id');
    }
}
