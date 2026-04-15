<?php

namespace Modules\Chat\App\Helpers;

class CpfHelper
{
    /**
     * Validar CPF
     */
    public static function validate(string $cpf): bool
    {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se todos os dígitos são iguais (ex: 111.111.111-11)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação dos dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * Formatar CPF
     */
    public static function format(string $cpf): string
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }

    /**
     * Limpar CPF (remover formatação)
     */
    public static function clean(string $cpf): string
    {
        return preg_replace('/[^0-9]/', '', $cpf);
    }

    /**
     * Mascarar CPF para exibição (LGPD)
     * Exemplo: 123.456.789-00 -> 123.***.***-00
     */
    public static function mask(string $cpf): string
    {
        $cpf = self::clean($cpf);
        if (strlen($cpf) != 11) {
            return '***.***.***-**';
        }
        return substr($cpf, 0, 3) . '.***.***-' . substr($cpf, 9, 2);
    }
}

