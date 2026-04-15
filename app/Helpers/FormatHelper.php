<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Formata um CPF
     *
     * @param string|null $cpf O CPF a ser formatado
     * @return string O CPF formatado ou vazio
     */
    public static function formatarCPF(?string $cpf): string
    {
        if (empty($cpf)) {
            return '';
        }

        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Formata o CPF
        if (strlen($cpf) === 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
        }

        return $cpf;
    }

    /**
     * Formata uma quantidade baseado na unidade de medida
     *
     * Regras:
     * - Para unidades inteiras (unidade, par, etc): mostra sem decimais
     * - Para outras unidades (kg, litro, metro, etc): mostra com decimais se necessário
     *
     * @param float|int|string $quantidade A quantidade a ser formatada
     * @param string|null $unidadeMedida A unidade de medida (ex: 'unidade', 'kg', 'metro', 'litro')
     * @return string A quantidade formatada
     */
    public static function formatarQuantidade($quantidade, ?string $unidadeMedida = null): string
    {
        $quantidade = (float) $quantidade;

        // Se não tiver unidade especificada, usar lógica padrão (2 decimais)
        if (empty($unidadeMedida)) {
            if ($quantidade == floor($quantidade)) {
                return number_format($quantidade, 0, ',', '.');
            }
            return number_format($quantidade, 2, ',', '.');
        }

        $unidade = strtolower(trim($unidadeMedida));

        // Unidades que sempre devem ser exibidas sem decimais
        $unidadesInteiras = [
            'unidade', 'unidades',
            'par', 'pares',
            'peça', 'peças',
            'item', 'itens',
            'caixa', 'caixas',
            'pacote', 'pacotes',
            'kit', 'kits',
            'rolo', 'rolos',
            'fardo', 'fardos',
            'saco', 'sacos',
            'embalagem', 'embalagens',
            'unid', 'und', 'un',
        ];

        // Para unidades inteiras, sempre mostrar sem decimais
        if (in_array($unidade, $unidadesInteiras)) {
            return number_format($quantidade, 0, ',', '.');
        }

        // Para outras unidades (kg, litro, metro, etc), mostrar decimais apenas se necessário
        if ($quantidade == floor($quantidade)) {
            // Se for número inteiro, mostrar sem decimais
            return number_format($quantidade, 0, ',', '.');
        }

        // Se tiver decimais, mostrar com 2 casas
        return number_format($quantidade, 2, ',', '.');
    }
}
