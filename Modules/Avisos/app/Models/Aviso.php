<?php

namespace Modules\Avisos\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Aviso extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titulo',
        'descricao',
        'conteudo',
        'tipo',
        'posicao',
        'estilo',
        'cor_primaria',
        'cor_secundaria',
        'imagem',
        'url_acao',
        'texto_botao',
        'botao_exibir',
        'dismissivel',
        'ativo',
        'destacar',
        'data_inicio',
        'data_fim',
        'ordem',
        'visualizacoes',
        'cliques',
        'configuracoes',
        'church_ids',
        'user_id',
    ];

    protected $casts = [
        'botao_exibir' => 'boolean',
        'dismissivel' => 'boolean',
        'ativo' => 'boolean',
        'destacar' => 'boolean',
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'configuracoes' => 'array',
        'church_ids' => 'array',
        'visualizacoes' => 'integer',
        'cliques' => 'integer',
        'ordem' => 'integer',
    ];

    /**
     * Relacionamento com usuário criador
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Scope para avisos ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true)
            ->where(function ($q) {
                $q->whereNull('data_inicio')
                    ->orWhere('data_inicio', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('data_fim')
                    ->orWhere('data_fim', '>=', now());
            });
    }

    /**
     * Scope para avisos por posição
     */
    public function scopePorPosicao($query, string $posicao)
    {
        return $query->where('posicao', $posicao);
    }

    /**
     * Scope para avisos por tipo
     */
    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Audiência por igreja: null ou [] = todas; caso contrário pelo menos um id em church_ids visível ao utilizador.
     */
    public function scopeForAudience($query, ?User $viewer)
    {
        if ($viewer && \Modules\Igrejas\App\Policies\ChurchPolicy::canBrowseAllChurches($viewer)) {
            return $query;
        }

        $global = function ($q): void {
            $q->whereNull('church_ids')->orWhere('church_ids', '[]');
        };

        if (! $viewer) {
            return $query->where(function ($q) use ($global) {
                $global($q);
            });
        }

        $ids = $viewer->affiliatedChurchIds();

        return $query->where(function ($q) use ($global, $ids) {
            $q->where(function ($q2) use ($global) {
                $global($q2);
            });
            foreach ($ids as $id) {
                $q->orWhereJsonContains('church_ids', (int) $id);
            }
        });
    }

    /**
     * Verifica se o aviso está ativo no momento
     */
    public function estaAtivo(): bool
    {
        if (!$this->ativo) {
            return false;
        }

        if ($this->data_inicio && $this->data_inicio->isFuture()) {
            return false;
        }

        if ($this->data_fim && $this->data_fim->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Incrementa visualizações
     */
    public function incrementarVisualizacao(): void
    {
        $this->increment('visualizacoes');
    }

    /**
     * Incrementa cliques
     */
    public function incrementarClique(): void
    {
        $this->increment('cliques');
    }

    /**
     * Accessor para tipo texto
     */
    public function getTipoTextoAttribute(): string
    {
        return match($this->tipo) {
            'info' => 'Informação',
            'success' => 'Sucesso',
            'warning' => 'Aviso',
            'danger' => 'Urgente',
            'promocao' => 'Promoção',
            'novidade' => 'Novidade',
            'anuncio' => 'Anúncio',
            default => 'Informação',
        };
    }

    /**
     * Accessor para posição texto
     */
    public function getPosicaoTextoAttribute(): string
    {
        return match($this->posicao) {
            'topo' => 'Topo da Página',
            'meio' => 'Meio da Página',
            'rodape' => 'Rodapé',
            'flutuante' => 'Flutuante',
            default => 'Topo',
        };
    }

    /**
     * Accessor para estilo texto
     */
    public function getEstiloTextoAttribute(): string
    {
        return match($this->estilo) {
            'banner' => 'Banner',
            'announcement' => 'Anúncio',
            'cta' => 'Call to Action',
            'modal' => 'Modal',
            'toast' => 'Toast',
            default => 'Banner',
        };
    }

    /**
     * Accessor para cor primária padrão
     */
    public function getCorPrimariaPadraoAttribute(): string
    {
        if ($this->cor_primaria) {
            return $this->cor_primaria;
        }

        return match($this->tipo) {
            'info' => 'indigo',
            'success' => 'emerald',
            'warning' => 'amber',
            'danger' => 'red',
            'promocao' => 'purple',
            'novidade' => 'blue',
            'anuncio' => 'gray',
            default => 'indigo',
        };
    }

    /**
     * Accessor para cor secundária padrão
     */
    public function getCorSecundariaPadraoAttribute(): string
    {
        if ($this->cor_secundaria) {
            return $this->cor_secundaria;
        }

        return match($this->tipo) {
            'info' => 'blue',
            'success' => 'green',
            'warning' => 'yellow',
            'danger' => 'rose',
            'promocao' => 'violet',
            'novidade' => 'cyan',
            'anuncio' => 'slate',
            default => 'blue',
        };
    }
}

