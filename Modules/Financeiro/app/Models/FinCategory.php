<?php

namespace Modules\Financeiro\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinCategory extends Model
{
    /** Receitas de inscrições e eventos (reconciliação Gateway / calendário). */
    public const CODE_REC_INSCRICOES_EVENTOS = 'REC_INSCR_EVENTOS';

    /** Despesa gerada ao pagar reembolso aprovado. */
    public const CODE_DES_REEMBOLSO = 'DES_REEMBOLSO';

    /** Grupos para relatório / contabilidade (plano simplificado). */
    public const GROUP_RECEITAS_OPERACIONAIS = 'receitas_operacionais';

    public const GROUP_RECEITAS_FINANCEIRAS = 'receitas_financeiras';

    public const GROUP_APLICACAO_DIRETA = 'aplicacao_direta';

    public const GROUP_DESPESAS_OPERACIONAIS = 'despesas_operacionais';

    public const GROUP_DESPESAS_ADMINISTRATIVAS = 'despesas_administrativas';

    public const GROUP_OUTROS = 'outros';

    protected $table = 'fin_categories';

    protected $fillable = [
        'name',
        'code',
        'group_key',
        'description',
        'direction',
        'sort_order',
        'is_active',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_system' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public static function groupLabel(?string $key): string
    {
        return match ($key) {
            self::GROUP_RECEITAS_OPERACIONAIS => 'Receitas operacionais',
            self::GROUP_RECEITAS_FINANCEIRAS => 'Receitas financeiras / património',
            self::GROUP_APLICACAO_DIRETA => 'Aplicação directa / projectos',
            self::GROUP_DESPESAS_OPERACIONAIS => 'Despesas operacionais',
            self::GROUP_DESPESAS_ADMINISTRATIVAS => 'Despesas administrativas',
            self::GROUP_OUTROS => 'Outros',
            default => $key ? (string) $key : '—',
        };
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(FinTransaction::class, 'category_id');
    }
}
