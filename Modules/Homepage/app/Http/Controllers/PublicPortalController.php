<?php

namespace Modules\Homepage\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Modules\Localidades\App\Models\Localidade;
use Modules\Pocos\App\Models\Poco;
use Modules\Agua\App\Models\RedeAgua;
use Modules\Agua\App\Models\PontoDistribuicao;
use Modules\Iluminacao\App\Models\PontoLuz;
use Modules\Estradas\App\Models\Trecho;
use Modules\Demandas\App\Models\Demanda;

class PublicPortalController extends Controller
{
    /**
     * Exibe o mapa público de infraestrutura (legado; view pode não existir nesta instalação).
     */
    public function index()
    {
        // Estatísticas gerais (LGPD compliant - apenas dados agregados)
        $estatisticas = $this->getEstatisticasPublicas();

        // Localidades ativas
        $localidades = Localidade::where('ativo', true)
            ->select('id', 'nome', 'codigo', 'tipo', 'cidade', 'estado', 'latitude', 'longitude', 'numero_moradores')
            ->orderBy('nome')
            ->get();

        return view('homepage::public.portal', compact('estatisticas', 'localidades'));
    }

    /**
     * Exibe informações detalhadas de uma localidade
     */
    public function localidade($id)
    {
        $localidade = Localidade::where('id', $id)
            ->where('ativo', true)
            ->firstOrFail();

        // Carregar infraestrutura da localidade (dados públicos)
        $infraestrutura = $this->getInfraestruturaLocalidade($localidade->id);

        return view('homepage::public.localidade', compact('localidade', 'infraestrutura'));
    }

    /**
     * API: Retorna dados de infraestrutura para mapa
     */
    public function apiInfraestrutura(Request $request)
    {
        try {
            $localidadeId = $request->get('localidade_id');

            $data = [
                'poços' => $this->getPocosPublicos($localidadeId),
                'pontos_agua' => $this->getPontosAguaPublicos($localidadeId),
                'pontos_luz' => $this->getPontosLuzPublicos($localidadeId),
                'estradas' => $this->getEstradasPublicas($localidadeId),
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados de infraestrutura pública', [
                'error' => $e->getMessage(),
                'ip' => request()->ip()
            ]);

            return response()->json(['error' => 'Erro ao processar solicitação'], 500);
        }
    }

    /**
     * API: Retorna estatísticas públicas
     */
    public function apiEstatisticas()
    {
        return response()->json($this->getEstatisticasPublicas());
    }

    /**
     * Obtém estatísticas públicas (LGPD compliant)
     */
    private function getEstatisticasPublicas(): array
    {
        $estatisticas = [
            'localidades' => 0,
            'poços' => 0,
            'poços_ativos' => 0,
            'pontos_agua' => 0,
            'pontos_luz' => 0,
            'pontos_luz_funcionando' => 0,
            'km_estradas' => 0,
            'demandas_abertas' => 0,
            'demandas_concluidas' => 0,
        ];

        try {
            // Localidades
            if (Schema::hasTable('localidades')) {
                $estatisticas['localidades'] = Localidade::where('ativo', true)->count();
            }

            // Poços
            if (Schema::hasTable('pocos')) {
                $estatisticas['poços'] = Poco::count();
                $estatisticas['poços_ativos'] = Poco::where('status', 'ativo')->count();
            }

            // Pontos de Água
            if (Schema::hasTable('pontos_distribuicao')) {
                $estatisticas['pontos_agua'] = PontoDistribuicao::count();
            }

            // Pontos de Luz
            if (Schema::hasTable('pontos_luz')) {
                $estatisticas['pontos_luz'] = PontoLuz::count();
                $estatisticas['pontos_luz_funcionando'] = PontoLuz::where('status', 'funcionando')->count();
            }

            // Estradas
            if (Schema::hasTable('trechos')) {
                $kmEstradas = Trecho::sum('extensao_km');
                $estatisticas['km_estradas'] = round($kmEstradas, 2);
            }

            // Demandas
            if (Schema::hasTable('demandas')) {
                $estatisticas['demandas_abertas'] = Demanda::where('status', 'aberta')->count();
                $estatisticas['demandas_concluidas'] = Demanda::where('status', 'concluida')->count();
            }
        } catch (\Exception $e) {
            Log::warning('Erro ao calcular estatísticas públicas', ['error' => $e->getMessage()]);
        }

        return $estatisticas;
    }

    /**
     * Obtém infraestrutura de uma localidade (dados públicos)
     */
    private function getInfraestruturaLocalidade(int $localidadeId): array
    {
        return [
            'poços' => $this->getPocosPublicos($localidadeId),
            'pontos_agua' => $this->getPontosAguaPublicos($localidadeId),
            'pontos_luz' => $this->getPontosLuzPublicos($localidadeId),
            'estradas' => $this->getEstradasPublicas($localidadeId),
        ];
    }

    /**
     * Obtém poços públicos (sem dados sensíveis)
     */
    private function getPocosPublicos(?int $localidadeId = null): array
    {
        if (!Schema::hasTable('pocos')) {
            return [];
        }

        $query = Poco::select([
            'id',
            'codigo',
            'localidade_id',
            'endereco',
            'latitude',
            'longitude',
            'status',
            'vazao_litros_hora',
            'proxima_manutencao'
        ])
        ->with('localidade:id,nome,codigo');

        if ($localidadeId) {
            $query->where('localidade_id', $localidadeId);
        }

        return $query->get()->map(function ($poco) {
            return [
                'id' => $poco->id,
                'codigo' => $poco->codigo,
                'localidade' => $poco->localidade ? $poco->localidade->nome : null,
                'endereco' => $poco->endereco,
                'latitude' => $poco->latitude,
                'longitude' => $poco->longitude,
                'status' => $poco->status,
                'status_texto' => $this->getStatusPocoTexto($poco->status),
                'vazao' => $poco->vazao_litros_hora,
                'proxima_manutencao' => $poco->proxima_manutencao ? $poco->proxima_manutencao->format('d/m/Y') : null,
            ];
        })->toArray();
    }

    /**
     * Obtém pontos de água públicos
     */
    private function getPontosAguaPublicos(?int $localidadeId = null): array
    {
        if (!Schema::hasTable('pontos_distribuicao')) {
            return [];
        }

        $query = PontoDistribuicao::select([
            'id',
            'codigo',
            'localidade_id',
            'endereco',
            'latitude',
            'longitude',
            'status',
            'numero_conexoes'
        ])
        ->with('localidade:id,nome,codigo');

        if ($localidadeId) {
            $query->where('localidade_id', $localidadeId);
        }

        return $query->get()->map(function ($ponto) {
            return [
                'id' => $ponto->id,
                'codigo' => $ponto->codigo,
                'localidade' => $ponto->localidade ? $ponto->localidade->nome : null,
                'endereco' => $ponto->endereco,
                'latitude' => $ponto->latitude,
                'longitude' => $ponto->longitude,
                'status' => $ponto->status,
                'conexoes' => $ponto->numero_conexoes,
            ];
        })->toArray();
    }

    /**
     * Obtém pontos de luz públicos
     */
    private function getPontosLuzPublicos(?int $localidadeId = null): array
    {
        if (!Schema::hasTable('pontos_luz')) {
            return [];
        }

        $query = PontoLuz::select([
            'id',
            'codigo',
            'localidade_id',
            'endereco',
            'latitude',
            'longitude',
            'status',
            'tipo_lampada',
            'potencia'
        ])
        ->with('localidade:id,nome,codigo');

        if ($localidadeId) {
            $query->where('localidade_id', $localidadeId);
        }

        return $query->get()->map(function ($ponto) {
            return [
                'id' => $ponto->id,
                'codigo' => $ponto->codigo,
                'localidade' => $ponto->localidade ? $ponto->localidade->nome : null,
                'endereco' => $ponto->endereco,
                'latitude' => $ponto->latitude,
                'longitude' => $ponto->longitude,
                'status' => $ponto->status,
                'status_texto' => $this->getStatusLuzTexto($ponto->status),
                'tipo_lampada' => $ponto->tipo_lampada,
                'potencia' => $ponto->potencia,
            ];
        })->toArray();
    }

    /**
     * Obtém estradas públicas
     */
    private function getEstradasPublicas(?int $localidadeId = null): array
    {
        if (!Schema::hasTable('trechos')) {
            return [];
        }

        $query = Trecho::select([
            'id',
            'codigo',
            'localidade_id',
            'nome',
            'extensao_km',
            'tipo_pavimento',
            'condicao'
        ])
        ->with('localidade:id,nome,codigo');

        if ($localidadeId) {
            $query->where('localidade_id', $localidadeId);
        }

        return $query->get()->map(function ($trecho) {
            return [
                'id' => $trecho->id,
                'codigo' => $trecho->codigo,
                'localidade' => $trecho->localidade ? $trecho->localidade->nome : null,
                'nome' => $trecho->nome,
                'extensao_km' => $trecho->extensao_km,
                'tipo_pavimento' => $trecho->tipo_pavimento,
                'tipo_pavimento_texto' => $trecho->tipo_pavimento_texto,
                'condicao' => $trecho->condicao,
                'condicao_texto' => $trecho->condicao_texto,
            ];
        })->toArray();
    }

    /**
     * Converte status de poço para texto
     */
    private function getStatusPocoTexto(string $status): string
    {
        $statusMap = [
            'ativo' => 'Ativo',
            'inativo' => 'Inativo',
            'manutencao' => 'Em Manutenção',
            'bomba_queimada' => 'Bomba Queimada',
        ];

        return $statusMap[$status] ?? ucfirst($status);
    }

    /**
     * Converte status de luz para texto
     */
    private function getStatusLuzTexto(string $status): string
    {
        $statusMap = [
            'funcionando' => 'Funcionando',
            'com_defeito' => 'Com Defeito',
            'desligado' => 'Desligado',
        ];

        return $statusMap[$status] ?? ucfirst($status);
    }
}

