<?php

namespace Modules\Igrejas\App\Services;

use Illuminate\Support\Facades\Http;

class CepLookupService
{
    /**
     * @return array{ok: bool, data?: array<string, mixed>, error?: string}
     */
    public function lookup(string $cep): array
    {
        $digits = preg_replace('/\D+/', '', $cep);
        if (strlen($digits) !== 8) {
            return ['ok' => false, 'error' => 'CEP deve ter 8 dígitos.'];
        }

        try {
            $response = Http::timeout(8)->get("https://viacep.com.br/ws/{$digits}/json/");
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => 'Falha ao consultar CEP.'];
        }

        if (! $response->successful()) {
            return ['ok' => false, 'error' => 'CEP não encontrado.'];
        }

        $json = $response->json();
        if (! is_array($json) || isset($json['erro'])) {
            return ['ok' => false, 'error' => 'CEP não encontrado.'];
        }

        return [
            'ok' => true,
            'data' => [
                'postal_code' => $digits,
                'street' => $json['logradouro'] ?? '',
                'district' => $json['bairro'] ?? '',
                'city' => $json['localidade'] ?? '',
                'state' => $json['uf'] ?? '',
                'complement_hint' => $json['complemento'] ?? '',
            ],
        ];
    }
}
