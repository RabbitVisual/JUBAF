<?php

namespace App\Helpers;

/**
 * Helper para mascarar dados sensíveis conforme LGPD
 * Lei Geral de Proteção de Dados - Lei nº 13.709/2018
 */
class LgpdHelper
{
    /**
     * Mascara um CPF mostrando apenas os 3 primeiros e 2 últimos dígitos
     * Ex: 123.456.789-10 -> 123.***.***-10
     */
    public static function maskCpf(?string $cpf): string
    {
        if (empty($cpf)) {
            return 'N/A';
        }

        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return '***.***.***-**';
        }

        return substr($cpf, 0, 3) . '.***.***-' . substr($cpf, -2);
    }

    /**
     * Mascara um NIS/PIS mostrando apenas os 3 primeiros e 2 últimos dígitos
     * Ex: 12404738676 -> 124.****.**6-76
     */
    public static function maskNis(?string $nis): string
    {
        if (empty($nis)) {
            return 'N/A';
        }

        // Remove caracteres não numéricos
        $nis = preg_replace('/[^0-9]/', '', $nis);

        if (strlen($nis) < 6) {
            return '***.*****.**-**';
        }

        return substr($nis, 0, 3) . '.*****.***-' . substr($nis, -2);
    }

    /**
     * Mascara um RG mostrando apenas os 2 primeiros e 2 últimos dígitos
     */
    public static function maskRg(?string $rg): string
    {
        if (empty($rg)) {
            return 'N/A';
        }

        // Remove caracteres não numéricos
        $rg = preg_replace('/[^0-9]/', '', $rg);

        if (strlen($rg) < 4) {
            return '**.***.***';
        }

        return substr($rg, 0, 2) . '.***.***-' . substr($rg, -1);
    }

    /**
     * Mascara um telefone mostrando apenas os 2 primeiros e 2 últimos dígitos
     * Ex: (75) 98113-2963 -> (75) ****-**63
     */
    public static function maskPhone(?string $phone): string
    {
        if (empty($phone)) {
            return 'N/A';
        }

        // Remove caracteres não numéricos
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) < 10) {
            return '(**) *****-****';
        }

        $ddd = substr($phone, 0, 2);
        $lastDigits = substr($phone, -2);

        if (strlen($phone) === 11) {
            return "({$ddd}) *****-**{$lastDigits}";
        }

        return "({$ddd}) ****-**{$lastDigits}";
    }

    /**
     * Mascara um email mostrando apenas as 2 primeiras letras e o domínio
     * Ex: usuario@email.com -> us***@email.com
     */
    public static function maskEmail(?string $email): string
    {
        if (empty($email)) {
            return 'N/A';
        }

        $parts = explode('@', $email);

        if (count($parts) !== 2) {
            return '***@***.***';
        }

        $username = $parts[0];
        $domain = $parts[1];

        if (strlen($username) <= 2) {
            return '***@' . $domain;
        }

        return substr($username, 0, 2) . '***@' . $domain;
    }

    /**
     * Mascara uma data de nascimento mostrando apenas o ano
     * Exemplo: 29/07/1971 retorna somente o ano (1971)
     */
    public static function maskBirthDate($date): string
    {
        if (empty($date)) {
            return 'N/A';
        }

        // Se for string, tenta extrair o ano
        if (is_string($date)) {
            // Tenta formato brasileiro DD/MM/YYYY
            if (preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $date, $matches)) {
                return '**/**/'. $matches[3];
            }
            // Tenta formato ISO YYYY-MM-DD
            if (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $date, $matches)) {
                return '**/**/' . $matches[1];
            }
            return '**/**/****';
        }

        // Se for objeto Carbon ou DateTime
        if ($date instanceof \DateTime || $date instanceof \Carbon\Carbon) {
            return '**/**/' . $date->format('Y');
        }

        return '**/**/****';
    }

    /**
     * Mascara um nome mostrando apenas o primeiro e último nome
     * Ex: João da Silva Santos -> João *** Santos
     */
    public static function maskName(?string $name): string
    {
        if (empty($name)) {
            return 'N/A';
        }

        $parts = explode(' ', trim($name));

        if (count($parts) === 1) {
            return $parts[0];
        }

        if (count($parts) === 2) {
            return $parts[0] . ' ' . $parts[1];
        }

        // Pega o primeiro e o último nome
        $firstName = $parts[0];
        $lastName = $parts[count($parts) - 1];

        return $firstName . ' *** ' . $lastName;
    }

    /**
     * Mascara um endereço mostrando apenas a rua e cidade
     * Remove números e complementos
     */
    public static function maskAddress(?string $address): string
    {
        if (empty($address)) {
            return 'N/A';
        }

        // Remove números
        $masked = preg_replace('/\d+/', '***', $address);

        return $masked;
    }

    /**
     * Mascara parcialmente uma string genérica
     * Mostra apenas os primeiros e últimos caracteres
     */
    public static function maskPartial(?string $value, int $showStart = 2, int $showEnd = 2): string
    {
        if (empty($value)) {
            return 'N/A';
        }

        $length = strlen($value);

        if ($length <= ($showStart + $showEnd)) {
            return str_repeat('*', $length);
        }

        $start = substr($value, 0, $showStart);
        $end = substr($value, -$showEnd);
        $middle = str_repeat('*', min($length - $showStart - $showEnd, 5));

        return $start . $middle . $end;
    }
    
    /**
     * Verifica se o usuário atual tem permissão para ver dados completos
     * Apenas administradores podem ver dados sensíveis sem máscara
     */
    public static function canViewSensitiveData(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Apenas admin e super-admin podem ver dados completos
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole(['admin', 'super-admin']);
        }

        return false;
    }
}

