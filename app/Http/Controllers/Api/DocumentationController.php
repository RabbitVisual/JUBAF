<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    /**
     * Exibe a documentação HTML da API
     */
    public function index()
    {
        $endpoints = $this->getEndpoints();
        return view('api.documentation', compact('endpoints'));
    }

    /**
     * Retorna JSON com informações da API
     */
    public function json()
    {
        return response()->json([
            'name' => 'JUBAF API',
            'version' => '1.0.0',
            'base_url' => url('/api/v1'),
            'endpoints' => $this->getEndpoints(),
        ]);
    }

    /**
     * Define todos os endpoints da API
     */
    private function getEndpoints(): array
    {
        return [
            'auth' => [
                'login' => [
                    'method' => 'POST',
                    'url' => '/api/v1/auth/login',
                    'description' => 'Autentica um usuário e retorna um token de acesso',
                    'public' => true,
                    'parameters' => [
                        'email' => ['required', 'string', 'email'],
                        'password' => ['required', 'string'],
                    ],
                    'response' => [
                        'success' => true,
                        'message' => 'Login realizado com sucesso',
                        'data' => [
                            'user' => ['id', 'name', 'email'],
                            'token' => 'string',
                            'token_type' => 'Bearer',
                        ],
                    ],
                ],
                'logout' => [
                    'method' => 'POST',
                    'url' => '/api/v1/auth/logout',
                    'description' => 'Revoga o token de acesso atual',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
                'me' => [
                    'method' => 'GET',
                    'url' => '/api/v1/auth/me',
                    'description' => 'Retorna informações do usuário autenticado',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
            ],
            'demandas' => [
                'index' => [
                    'method' => 'GET',
                    'url' => '/api/v1/demandas',
                    'description' => 'Lista todas as demandas (com filtros opcionais)',
                    'public' => false,
                    'auth' => 'Bearer Token',
                    'parameters' => [
                        'status' => ['optional', 'string', 'aberta|em_andamento|concluida|cancelada'],
                        'tipo' => ['optional', 'string', 'agua|luz|estrada|poco'],
                        'prioridade' => ['optional', 'string', 'baixa|media|alta|urgente'],
                        'localidade_id' => ['optional', 'integer'],
                        'search' => ['optional', 'string'],
                        'per_page' => ['optional', 'integer', 'default: 20'],
                    ],
                ],
                'show' => [
                    'method' => 'GET',
                    'url' => '/api/v1/demandas/{id}',
                    'description' => 'Retorna uma demanda específica',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
                'store' => [
                    'method' => 'POST',
                    'url' => '/api/v1/demandas',
                    'description' => 'Cria uma nova demanda',
                    'public' => false,
                    'auth' => 'Bearer Token',
                    'parameters' => [
                        'solicitante_nome' => ['required', 'string', 'max:255'],
                        'solicitante_telefone' => ['required', 'string', 'max:20'],
                        'solicitante_email' => ['optional', 'email'],
                        'localidade_id' => ['required', 'integer'],
                        'tipo' => ['required', 'string', 'agua|luz|estrada|poco'],
                        'prioridade' => ['required', 'string', 'baixa|media|alta|urgente'],
                        'motivo' => ['required', 'string', 'max:255'],
                        'descricao' => ['required', 'string', 'min:20'],
                    ],
                ],
                'update' => [
                    'method' => 'PUT',
                    'url' => '/api/v1/demandas/{id}',
                    'description' => 'Atualiza uma demanda existente',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
                'destroy' => [
                    'method' => 'DELETE',
                    'url' => '/api/v1/demandas/{id}',
                    'description' => 'Remove uma demanda',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
                'stats' => [
                    'method' => 'GET',
                    'url' => '/api/v1/demandas/stats',
                    'description' => 'Retorna estatísticas de demandas',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
            ],
            'ordens' => [
                'index' => [
                    'method' => 'GET',
                    'url' => '/api/v1/ordens',
                    'description' => 'Lista todas as ordens de serviço',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
                'show' => [
                    'method' => 'GET',
                    'url' => '/api/v1/ordens/{id}',
                    'description' => 'Retorna uma ordem de serviço específica',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
                'store' => [
                    'method' => 'POST',
                    'url' => '/api/v1/ordens',
                    'description' => 'Cria uma nova ordem de serviço',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
                'stats' => [
                    'method' => 'GET',
                    'url' => '/api/v1/ordens/stats',
                    'description' => 'Retorna estatísticas de ordens de serviço',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
            ],
            'localidades' => [
                'index' => [
                    'method' => 'GET',
                    'url' => '/api/v1/localidades',
                    'description' => 'Lista todas as localidades (público)',
                    'public' => true,
                ],
                'show' => [
                    'method' => 'GET',
                    'url' => '/api/v1/localidades/{id}',
                    'description' => 'Retorna uma localidade específica (público)',
                    'public' => true,
                ],
            ],
            'materiais' => [
                'index' => [
                    'method' => 'GET',
                    'url' => '/api/v1/materiais',
                    'description' => 'Lista todos os materiais',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
                'show' => [
                    'method' => 'GET',
                    'url' => '/api/v1/materiais/{id}',
                    'description' => 'Retorna um material específico',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
            ],
            'equipes' => [
                'index' => [
                    'method' => 'GET',
                    'url' => '/api/v1/equipes',
                    'description' => 'Lista todas as equipes',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
                'show' => [
                    'method' => 'GET',
                    'url' => '/api/v1/equipes/{id}',
                    'description' => 'Retorna uma equipe específica',
                    'public' => false,
                    'auth' => 'Bearer Token',
                ],
            ],
        ];
    }
}

