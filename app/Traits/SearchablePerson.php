<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Modules\Pessoas\App\Models\PessoaCad;

trait SearchablePerson
{
    /**
     * Busca pessoas via AJAX
     */
    public function buscarPessoa(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $pessoas = PessoaCad::with('localidade')
            ->where('ativo', true)
            ->where(function($q) use ($query) {
                $q->where('nom_pessoa', 'like', "%{$query}%")
                  ->orWhere('nom_apelido_pessoa', 'like', "%{$query}%")
                  ->orWhere('num_nis_pessoa_atual', 'like', "%{$query}%")
                  ->orWhere('num_cpf_pessoa', 'like', "%{$query}%");
            })
            ->limit(15)
            ->get()
            ->map(function($pessoa) {
                return [
                    'id' => $pessoa->id, // Usar o nome correto da PK: id
                    'nome' => $pessoa->nom_pessoa,
                    'apelido' => $pessoa->nom_apelido_pessoa,
                    'nis' => $pessoa->num_nis_pessoa_atual,
                    'nis_formatado' => $pessoa->nis_formatado ?? $pessoa->num_nis_pessoa_atual,
                    'cpf' => $pessoa->num_cpf_pessoa,
                    'cpf_formatado' => $pessoa->cpf_formatado ?? $pessoa->num_cpf_pessoa,
                    'localidade_id' => $pessoa->localidade_id,
                    'localidade_nome' => $pessoa->localidade ? $pessoa->localidade->nome : null,
                    'data_nascimento' => $pessoa->data_nascimento ? $pessoa->data_nascimento->format('d/m/Y') : null,
                    'idade' => $pessoa->idade,
                    'recebe_pbf' => $pessoa->recebe_pbf,
                ];
            });

        return response()->json($pessoas);
    }

    /**
     * Obtém detalhes de uma pessoa via AJAX
     */
    public function obterPessoa($id)
    {
        $pessoa = PessoaCad::with('localidade')->where('id', $id)->firstOrFail();

        return response()->json([
            'id' => $pessoa->id,
            'nome' => $pessoa->nom_pessoa,
            'apelido' => $pessoa->nom_apelido_pessoa,
            'nis' => $pessoa->num_nis_pessoa_atual,
            'nis_formatado' => $pessoa->nis_formatado ?? $pessoa->num_nis_pessoa_atual,
            'cpf' => $pessoa->num_cpf_pessoa,
            'cpf_formatado' => $pessoa->cpf_formatado ?? $pessoa->num_cpf_pessoa,
            'localidade_id' => $pessoa->localidade_id,
            'localidade_nome' => $pessoa->localidade ? $pessoa->localidade->nome : null,
            'data_nascimento' => $pessoa->data_nascimento ? $pessoa->data_nascimento->format('d/m/Y') : null,
            'idade' => $pessoa->idade,
            'recebe_pbf' => $pessoa->recebe_pbf,
            'telefone' => null, // Campo não existe na tabela pessoas_cad
            'email' => null,    // Campo não existe na tabela pessoas_cad
        ]);
    }
}
