<?php

namespace Modules\Treasury\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PaymentGateway\App\Models\Payment;

class FinancialEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'category',
        'category_id',
        'title',
        'description',
        'amount',
        'entry_date',
        'user_id',
        'member_id',
        'payment_id',
        'campaign_id',
        'goal_id',
        'reversal_of_id',
        'payment_method',
        'reference_number',
        'metadata',
        'expense_status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'entry_date' => 'date',
        'metadata' => 'array',
    ];

    public const EXPENSE_STATUS_PENDING = 'pending';

    public const EXPENSE_STATUS_APPROVED = 'approved';

    public const EXPENSE_STATUS_PAID = 'paid';

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com pagamento (PaymentGateway)
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Relacionamento com campanha
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Relacionamento com meta financeira
     */
    public function goal(): BelongsTo
    {
        return $this->belongsTo(FinancialGoal::class);
    }


    public function financialCategory(): BelongsTo
    {
        return $this->belongsTo(FinancialCategory::class, 'category_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function fund(): BelongsTo
    {
        return $this->belongsTo(FinancialFund::class, 'fund_id');
    }

    public function reversalOf(): BelongsTo
    {
        return $this->belongsTo(FinancialEntry::class, 'reversal_of_id');
    }

    /**
     * Scope para entradas (receitas)
     */
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    /**
     * Scope para saídas (despesas)
     */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    /**
     * Scope por categoria
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope por período
     */
    public function scopePeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('entry_date', [$startDate, $endDate]);
    }

    /**
     * Scope por mês
     */
    public function scopeMonth($query, $year, $month)
    {
        return $query->whereYear('entry_date', $year)
            ->whereMonth('entry_date', $month);
    }

    /**
     * Scope por ano
     */
    public function scopeYear($query, $year)
    {
        return $query->whereYear('entry_date', $year);
    }
}
