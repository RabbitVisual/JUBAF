<?php

namespace Modules\Financeiro\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinBankAccount extends Model
{
    public const TYPE_CORRENTE = 'corrente';

    public const TYPE_POUPANCA = 'poupanca';

    protected $table = 'fin_bank_accounts';

    protected $fillable = [
        'name',
        'institution',
        'account_type',
        'currency',
        'balance',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(FinTransaction::class, 'bank_account_id');
    }
}
