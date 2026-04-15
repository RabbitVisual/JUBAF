<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait DemandasSearchable
{
    /**
     * Aplica filtros de busca e critérios comuns para Demandas
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function applyDemandasFilters(Builder $query, array $filters): Builder
    {
        // Busca textual (Global Search)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('solicitante_nome', 'like', "%{$search}%")
                  ->orWhere('solicitante_apelido', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%")
                  ->orWhere('motivo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhereHas('pessoa', function($pq) use ($search) {
                      $pq->where('nom_pessoa', 'like', "%{$search}%")
                        ->orWhere('nom_apelido_pessoa', 'like', "%{$search}%")
                        ->orWhere('num_cpf_pessoa', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por Status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filtro por Tipo (agua, luz, estrada, poco)
        if (!empty($filters['tipo'])) {
            $query->where('tipo', $filters['tipo']);
        }

        // Filtro por Prioridade (baixa, media, alta, urgente)
        if (!empty($filters['prioridade'])) {
            $query->where('prioridade', $filters['prioridade']);
        }

        // Filtro por Localidade
        if (!empty($filters['localidade_id'])) {
            $query->where('localidade_id', $filters['localidade_id']);
        }

        return $query;
    }

    /**
     * Retorna os relacionamentos padrão recomendados para listagens de Demandas
     *
     * @return array
     */
    public function getDemandasDefaultRelations(): array
    {
        return ['localidade', 'pessoa', 'usuario', 'ordemServico'];
    }
}
