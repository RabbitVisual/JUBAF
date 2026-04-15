<?php

namespace Modules\Avisos\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Avisos\App\Services\AvisoService;
use Modules\Avisos\App\Models\Aviso;

class AvisosPublicController extends Controller
{
    protected AvisoService $avisoService;

    public function __construct(AvisoService $avisoService)
    {
        $this->avisoService = $avisoService;
    }

    /**
     * Meta para polling (atualização quase em tempo real na página pública).
     */
    public function feedMeta()
    {
        $viewer = auth()->user();
        $latest = Aviso::query()
            ->ativos()
            ->forAudience($viewer)
            ->max('updated_at');

        return response()->json([
            'success' => true,
            'updated_at' => $latest ? \Carbon\Carbon::parse($latest)->toIso8601String() : null,
        ]);
    }

    /**
     * Obter avisos por posição (API)
     */
    public function obterPorPosicao(string $posicao)
    {
        $avisos = $this->avisoService->obterAvisosPorPosicao($posicao, null, auth()->user());

        return response()->json([
            'success' => true,
            'data' => $avisos->map(function ($aviso) {
                $author = $aviso->usuario;

                return [
                    'id' => $aviso->id,
                    'titulo' => $aviso->titulo,
                    'descricao' => $aviso->descricao,
                    'conteudo' => $aviso->conteudo,
                    'tipo' => $aviso->tipo,
                    'posicao' => $aviso->posicao,
                    'estilo' => $aviso->estilo,
                    'cor_primaria' => $aviso->cor_primaria_padrao,
                    'cor_secundaria' => $aviso->cor_secundaria_padrao,
                    'imagem' => $aviso->imagem ? asset('storage/' . $aviso->imagem) : null,
                    'url_acao' => $aviso->url_acao,
                    'texto_botao' => $aviso->texto_botao,
                    'botao_exibir' => $aviso->botao_exibir,
                    'dismissivel' => $aviso->dismissivel,
                    'destacar' => $aviso->destacar,
                    'autor' => $author ? [
                        'nome' => $author->name,
                        'foto' => user_photo_url($author),
                        'oficial' => user_is_aviso_official_author($author),
                    ] : null,
                ];
            }),
        ]);
    }

    /**
     * Registrar visualização
     */
    public function registrarVisualizacao(Request $request, int $id)
    {
        $this->avisoService->registrarVisualizacao($id);

        return response()->json([
            'success' => true,
            'message' => 'Visualização registrada',
        ]);
    }

    /**
     * Registrar clique
     */
    public function registrarClique(Request $request, int $id)
    {
        $this->avisoService->registrarClique($id);

        return response()->json([
            'success' => true,
            'message' => 'Clique registrado',
        ]);
    }
}

