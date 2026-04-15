<?php

namespace App\Traits;

/**
 * @property string $codeField
 */
trait GeneratesCode
{
    /**
     * Define o campo de código (pode ser sobrescrito pelo modelo)
     * Padrão: 'codigo'
     * Para OrdemServico: 'numero'
     */
    protected static function getCodeField(): string
    {
        // Se o modelo definir um campo específico, usar esse
        if (property_exists(static::class, 'codeField')) {
            return static::$codeField;
        }

        // Verificar se o modelo tem 'numero' no fillable (caso de OrdemServico)
        /** @var \Illuminate\Database\Eloquent\Model|static $instance */
        $instance = new static;
        if (in_array('numero', $instance->getFillable()) && ! in_array('codigo', $instance->getFillable())) {
            return 'numero';
        }

        return 'codigo';
    }

    /**
     * Gera um código único baseado no prefixo e tipo do módulo
     *
     * @param  string  $prefix  Prefixo do código (ex: 'DEM', 'OS')
     * @param  string|null  $tipo  Tipo opcional para subcategorização (ex: 'luz', 'agua')
     * @return string Código gerado (ex: 'OS-LUZ-202511-0001')
     */
    public static function generateCode(string $prefix, ?string $tipo = null): string
    {
        $year = date('Y');
        $month = date('m');
        $codeField = static::getCodeField();

        // Monta o prefixo do código
        $codePrefix = strtoupper($prefix);
        if ($tipo) {
            $codePrefix .= '-'.strtoupper(substr($tipo, 0, 3));
        }
        $codePrefix .= '-'.$year.$month.'-';

        // Busca o último código do mesmo tipo e período
        $query = static::where($codeField, 'like', $codePrefix.'%')
            ->orderBy($codeField, 'desc');

        // dump([
        //     'class' => static::class,
        //     'table' => (new static)->getTable(),
        //     'codeField' => $codeField,
        //     'sql' => $query->toSql(),
        //     'bindings' => $query->getBindings()
        // ]);

        $lastCode = $query->value($codeField);

        if ($lastCode) {
            // Extrai o número sequencial
            $lastNumber = (int) substr($lastCode, strrpos($lastCode, '-') + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Formata o número com zeros à esquerda (4 dígitos)
        $code = $codePrefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        // Verifica se o código já existe (caso raro de colisão)
        while (static::where($codeField, $code)->exists()) {
            $nextNumber++;
            $code = $codePrefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
        }

        return $code;
    }
}
