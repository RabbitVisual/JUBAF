<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // SEGURANÇA: Redirecionar usuários para seus dashboards específicos
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasRole('lider')) {
                return redirect()->route('lideres.dashboard');
            }

            if ($user->hasRole('jovens')) {
                try {
                    return redirect()->route('jovens.dashboard');
                } catch (\Exception $e) {
                    return redirect('/jovens/dashboard');
                }
            }

            if (user_can_access_admin_panel($user)) {
                return redirect()->route('admin.dashboard');
            }

            if (user_can_access_diretoria_panel($user)) {
                return redirect()->route('diretoria.dashboard');
            }

            if (user_can_access_pastor_panel($user)) {
                return redirect()->route('pastor.dashboard');
            }
        }

        $period = $request->get('period', 'today');

        // Cache por 5 minutos para melhor performance (com período no cache key)
        // IMPORTANTE: Cache é limpo quando demandas são excluídas
        $stats = Cache::remember("dashboard_stats_{$period}", 300, function () use ($period) {
            return [
                // Demandas (excluindo soft deleted)
                'demandas_abertas' => $this->safeQuery('demandas', function($query) use ($period) {
                    $query = $query->where('status', 'aberta')
                        ->whereNull('deleted_at'); // Excluir soft deleted
                    return $this->applyPeriodFilter($query, $period)->count();
                }, 0),
                'demandas_total' => $this->safeQuery('demandas', function($query) {
                    return $query->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'demandas_concluidas' => $this->safeQuery('demandas', function($query) {
                    return $query->where('status', 'concluida')
                        ->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'demandas_em_andamento' => $this->safeQuery('demandas', function($query) {
                    return $query->where('status', 'em_andamento')
                        ->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),

                // Ordens de Serviço (excluindo soft deleted)
                'os_execucao' => $this->safeQuery('ordens_servico', function($query) {
                    return $query->where('status', 'em_execucao')
                        ->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'os_total' => $this->safeQuery('ordens_servico', function($query) {
                    return $query->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'os_concluidas' => $this->safeQuery('ordens_servico', function($query) use ($period) {
                    $query = $query->where('status', 'concluida')
                        ->whereNull('deleted_at'); // Excluir soft deleted
                    return $this->applyPeriodFilter($query, $period, 'data_conclusao')->count();
                }, 0),
                'os_pendentes' => $this->safeQuery('ordens_servico', function($query) {
                    return $query->where('status', 'pendente')
                        ->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),

                // Materiais (excluindo soft deleted)
                'materiais_baixo_estoque' => $this->safeQuery('materiais', function($query) {
                    return $query->whereColumn('quantidade_estoque', '<=', 'quantidade_minima')
                        ->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'materiais_total' => $this->safeQuery('materiais', function($query) {
                    return $query->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'materiais_ativos' => $this->safeQuery('materiais', function($query) {
                    return $query->where('ativo', true)
                        ->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'materiais_sem_estoque' => $this->safeQuery('materiais', function($query) {
                    return $query->where('quantidade_estoque', '<=', 0)
                        ->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),

                // Localidades
                'localidades_total' => $this->safeQuery('localidades', function($query) {
                    return $query->where('ativo', true)->count();
                }, 0),

                // Equipes (excluindo soft deleted)
                'equipes_ativas' => $this->safeQuery('equipes', function($query) {
                    return $query->where('ativo', true)
                        ->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'equipes_total' => $this->safeQuery('equipes', function($query) {
                    return $query->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),

                // Funcionários (excluindo soft deleted)
                'funcionarios_total' => $this->safeQuery('funcionarios', function($query) {
                    return $query->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'funcionarios_ativos' => $this->safeQuery('funcionarios', function($query) {
                    return $query->where('ativo', true)
                        ->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'funcionarios_com_equipe' => $this->safeQuery('equipe_funcionarios', function($query) {
                    return $query->distinct('funcionario_id')->count('funcionario_id');
                }, 0),

                // Pessoas
                'pessoas_total' => $this->safeQuery('pessoas_cad', function($query) {
                    return $query->whereNull('deleted_at')->count();
                }, 0),
                'pessoas_beneficiarias' => $this->safeQuery('pessoas_cad', function($query) {
                    return $query->whereNotNull('ref_pbf')
                        ->where('ref_pbf', '>', 0)
                        ->whereNull('deleted_at')
                        ->count();
                }, 0),

                // Infraestrutura (excluindo soft deleted)
                'pontos_luz_total' => $this->safeQuery('pontos_luz', function($query) {
                    return $query->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'pontos_luz_ativos' => $this->safeQuery('pontos_luz', function($query) {
                    $query = $query->whereNull('deleted_at'); // Excluir soft deleted
                    if (Schema::hasColumn('pontos_luz', 'status')) {
                        return $query->where('status', 'funcionando')->count();
                    } elseif (Schema::hasColumn('pontos_luz', 'ativo')) {
                        return $query->where('ativo', true)->count();
                    }
                    return $query->count();
                }, 0),
                'redes_agua_total' => $this->safeQuery('redes_agua', function($query) {
                    return $query->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'redes_agua_ativas' => $this->safeQuery('redes_agua', function($query) {
                    $query = $query->whereNull('deleted_at'); // Excluir soft deleted
                    if (Schema::hasColumn('redes_agua', 'status')) {
                        return $query->where('status', 'funcionando')->count();
                    } elseif (Schema::hasColumn('redes_agua', 'ativo')) {
                        return $query->where('ativo', true)->count();
                    }
                    return $query->count();
                }, 0),
                'pocos_total' => $this->safeQuery('pocos', function($query) {
                    return $query->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
                'pocos_ativos' => $this->safeQuery('pocos', function($query) {
                    $query = $query->whereNull('deleted_at'); // Excluir soft deleted
                    if (Schema::hasColumn('pocos', 'status')) {
                        return $query->where('status', 'ativo')->count();
                    } elseif (Schema::hasColumn('pocos', 'ativo')) {
                        return $query->where('ativo', true)->count();
                    }
                    return $query->count();
                }, 0),
                'trechos_total' => $this->safeQuery('trechos', function($query) {
                    return $query->whereNull('deleted_at')->count(); // Excluir soft deleted
                }, 0),
            ];
        });

        // Dados para gráficos
        $chartData = [
            'demandas_por_status' => $this->getDemandasPorStatus(),
            'os_por_mes' => $this->getOSPorMes(),
            'demandas_por_tipo' => $this->getDemandasPorTipo(),
            'materiais_estoque' => $this->getMateriaisEstoque(),
            'os_por_status' => $this->getOSPorStatus(),
            'infraestrutura_resumo' => $this->getInfraestruturaResumo(),
            'materiais_por_categoria' => $this->getMateriaisPorCategoria(),
        ];

        $ultimas_demandas = $this->safeQuery('demandas', function($query) use ($period) {
            $query = $this->applyPeriodFilter($query, $period);
            return $query->whereNull('deleted_at') // Excluir soft deleted
                ->orderBy('created_at', 'desc')->limit(5)->get();
        }, collect());

        $os_execucao = $this->safeQuery('ordens_servico', function($query) use ($period) {
            $query = $query->where('status', 'em_execucao')
                ->whereNull('deleted_at'); // Excluir soft deleted
            $query = $this->applyPeriodFilter($query, $period, 'data_inicio');
            return $query->orderBy('data_inicio', 'asc')->limit(5)->get();
        }, collect());

        $ultimas_os = $this->safeQuery('ordens_servico', function($query) use ($period) {
            $query = $query->whereNull('deleted_at'); // Excluir soft deleted
            $query = $this->applyPeriodFilter($query, $period);
            return $query->orderBy('created_at', 'desc')->limit(5)->get();
        }, collect());

        $alertas_criticos = $this->getAlertasCriticos();

        // Se chegou aqui, o usuário não tem role específica
        // Redirecionar para login com mensagem
        Auth::logout();
        return redirect()->route('login')->with('error', 'Usuário sem permissões. Entre em contato com o administrador.');
    }

    /**
     * Aplica filtro de período na query
     */
    private function applyPeriodFilter($query, string $period, string $dateColumn = 'created_at')
    {
        switch ($period) {
            case 'today':
                $query->whereDate($dateColumn, today());
                break;
            case 'week':
                $query->whereBetween($dateColumn, [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth($dateColumn, now()->month)
                      ->whereYear($dateColumn, now()->year);
                break;
            default:
                // Sem filtro
                break;
        }
        return $query;
    }

    private function getDemandasPorStatus()
    {
        return $this->safeQuery('demandas', function($query) {
            return $query->select('status', DB::raw('count(*) as total'))
                ->whereNull('deleted_at') // Excluir soft deleted
                ->groupBy('status')
                ->get()
                ->pluck('total', 'status')
                ->toArray();
        }, []);
    }

    private function getOSPorMes()
    {
        return $this->safeQuery('ordens_servico', function($query) {
            return $query->select(
                    DB::raw('MONTH(data_abertura) as mes'),
                    DB::raw('YEAR(data_abertura) as ano'),
                    DB::raw('count(*) as total')
                )
                ->whereYear('data_abertura', now()->year)
                ->whereNull('deleted_at') // Excluir soft deleted
                ->groupBy('mes', 'ano')
                ->orderBy('mes')
                ->get()
                ->mapWithKeys(function($item) {
                    $mesNome = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
                    return [$mesNome[$item->mes - 1] => $item->total];
                })
                ->toArray();
        }, []);
    }

    private function getDemandasPorTipo()
    {
        return $this->safeQuery('demandas', function($query) {
            return $query->select('tipo', DB::raw('count(*) as total'))
                ->whereNull('deleted_at') // Excluir soft deleted
                ->groupBy('tipo')
                ->get()
                ->pluck('total', 'tipo')
                ->toArray();
        }, []);
    }

    private function getMateriaisEstoque()
    {
        return $this->safeQuery('materiais', function($query) {
            return $query->select('nome', 'quantidade_estoque', 'quantidade_minima', 'unidade_medida')
                ->whereColumn('quantidade_estoque', '<=', 'quantidade_minima')
                ->where('ativo', true)
                ->whereNull('deleted_at') // Excluir soft deleted
                ->orderBy('quantidade_estoque', 'asc')
                ->limit(5)
                ->get()
                ->toArray();
        }, []);
    }

    private function getOSPorStatus()
    {
        return $this->safeQuery('ordens_servico', function($query) {
            return $query->select('status', DB::raw('count(*) as total'))
                ->whereNull('deleted_at') // Excluir soft deleted
                ->groupBy('status')
                ->get()
                ->pluck('total', 'status')
                ->toArray();
        }, []);
    }

    private function getInfraestruturaResumo()
    {
        try {
            $data = [];

            // Pontos de Luz (módulo Iluminacao)
            if (Schema::hasTable('pontos_luz')) {
                $query = DB::table('pontos_luz');
                if (Schema::hasColumn('pontos_luz', 'deleted_at')) {
                    $query->whereNull('deleted_at');
                }
                $data['pontos_luz'] = $query->count();
            } else {
                $data['pontos_luz'] = 0;
            }

            // Redes de Água (módulo Agua)
            if (Schema::hasTable('redes_agua')) {
                $query = DB::table('redes_agua');
                if (Schema::hasColumn('redes_agua', 'deleted_at')) {
                    $query->whereNull('deleted_at');
                }
                $data['redes_agua'] = $query->count();
            } else {
                $data['redes_agua'] = 0;
            }

            // Poços (módulo Pocos)
            if (Schema::hasTable('pocos')) {
                $query = DB::table('pocos');
                if (Schema::hasColumn('pocos', 'deleted_at')) {
                    $query->whereNull('deleted_at');
                }
                $data['pocos'] = $query->count();
            } else {
                $data['pocos'] = 0;
            }

            // Trechos (módulo Estradas)
            if (Schema::hasTable('trechos')) {
                $query = DB::table('trechos');
                if (Schema::hasColumn('trechos', 'deleted_at')) {
                    $query->whereNull('deleted_at');
                }
                $data['trechos'] = $query->count();
            } else {
                $data['trechos'] = 0;
            }

            return $data;
        } catch (\Exception $e) {
            \Log::error('Erro ao coletar dados de infraestrutura: ' . $e->getMessage());
            return [
                'pontos_luz' => 0,
                'redes_agua' => 0,
                'pocos' => 0,
                'trechos' => 0,
            ];
        }
    }

    private function getMateriaisPorCategoria()
    {
        return $this->safeQuery('materiais', function($query) {
            return $query->select('categoria', DB::raw('count(*) as total'))
                ->where('ativo', true)
                ->whereNull('deleted_at') // Excluir soft deleted
                ->groupBy('categoria')
                ->get()
                ->mapWithKeys(function($item) {
                    $categorias = [
                        'lampadas' => 'Lâmpadas',
                        'reatores' => 'Reatores',
                        'fios' => 'Fios',
                        'canos' => 'Canos',
                        'conexoes' => 'Conexões',
                        'combustivel' => 'Combustível',
                        'pecas_pocos' => 'Peças para Poços',
                        'outros' => 'Outros'
                    ];
                    return [$categorias[$item->categoria] ?? ucfirst($item->categoria) => $item->total];
                })
                ->toArray();
        }, []);
    }

    private function getAlertasCriticos()
    {
        $alertas = [];

        // Materiais com estoque baixo
        $materiaisBaixo = $this->safeQuery('materiais', function($query) {
            return $query->whereColumn('quantidade_estoque', '<=', 'quantidade_minima')
                ->whereNull('deleted_at')->count(); // Excluir soft deleted
        }, 0);
        if ($materiaisBaixo > 0) {
            $alertas[] = [
                'tipo' => 'warning',
                'icone' => 'bi-exclamation-triangle',
                'titulo' => 'Materiais com Estoque Baixo',
                'mensagem' => "{$materiaisBaixo} material(is) necessitam reposição urgente.",
                'link' => route('materiais.index') . '?filter=estoque_baixo',
            ];
        }

        // Demandas urgentes
        $demandasUrgentes = $this->safeQuery('demandas', function($query) {
            return $query->where('prioridade', 'urgente')
                ->where('status', '!=', 'concluida')
                ->whereNull('deleted_at') // Excluir soft deleted
                ->count();
        }, 0);
        if ($demandasUrgentes > 0) {
            $alertas[] = [
                'tipo' => 'danger',
                'icone' => 'bi-exclamation-circle',
                'titulo' => 'Demandas Urgentes',
                'mensagem' => "{$demandasUrgentes} demanda(s) com prioridade urgente aguardando atendimento.",
                'link' => route('demandas.index') . '?prioridade=urgente',
            ];
        }

        // OS atrasadas
        $osAtrasadas = $this->safeQuery('ordens_servico', function($query) {
            return $query->where('status', 'em_execucao')
                ->where('data_inicio', '<', now()->subDays(7))
                ->count();
        }, 0);
        if ($osAtrasadas > 0) {
            $alertas[] = [
                'tipo' => 'info',
                'icone' => 'bi-clock-history',
                'titulo' => 'OS em Execução',
                'mensagem' => "{$osAtrasadas} ordem(ns) de serviço em execução há mais de 7 dias.",
                'link' => route('ordens.index') . '?status=em_execucao',
            ];
        }

        return $alertas;
    }

    /**
     * Executa uma query de forma segura, retornando valor padrão se a tabela não existir
     */
    private function safeQuery(string $table, callable $callback, $default = null)
    {
        try {
            if (!Schema::hasTable($table)) {
                return $default;
            }

            $query = DB::table($table);
            return $callback($query);
        } catch (\Exception $e) {
            return $default;
        }
    }
}

