<?php

namespace Modules\Avisos\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Modules\Avisos\App\Models\Aviso;

class AvisosAdminController extends Controller
{
    protected function igrejasChurchesForForms(): Collection
    {
        if (! module_enabled('Igrejas')) {
            return collect();
        }

        return \Modules\Igrejas\App\Models\Church::query()->orderBy('name')->get(['id', 'name']);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function mergeChurchIdsFromRequest(Request $request, array $validated): array
    {
        $ids = array_values(array_filter(array_map('intval', (array) $request->input('church_ids', []))));
        $validated['church_ids'] = $ids === [] ? null : $ids;

        return $validated;
    }

    protected function avisosView(string $name): string
    {
        $root = request()->routeIs('diretoria.*') ? 'avisos::paineldiretoria' : 'avisos::admin';

        return $root.'.'.$name;
    }

    protected function avisosRoute(string $suffix): string
    {
        return request()->routeIs('diretoria.*')
            ? 'diretoria.avisos.'.$suffix
            : 'admin.avisos.'.$suffix;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Aviso::class);

        $query = Aviso::query()->with('usuario');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('posicao')) {
            $query->where('posicao', $request->posicao);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo === '1');
        }

        $avisos = $query->orderBy('ordem', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Estatísticas
        $estatisticas = [
            'total' => Aviso::count(),
            'ativos' => Aviso::ativos()->count(),
            'inativos' => Aviso::where('ativo', false)->count(),
            'por_tipo' => Aviso::selectRaw('tipo, count(*) as total')
                ->groupBy('tipo')
                ->pluck('total', 'tipo')
                ->toArray(),
            'por_estilo' => Aviso::selectRaw('estilo, count(*) as total')
                ->groupBy('estilo')
                ->pluck('total', 'estilo')
                ->toArray(),
            'por_posicao' => Aviso::selectRaw('posicao, count(*) as total')
                ->groupBy('posicao')
                ->pluck('total', 'posicao')
                ->toArray(),
        ];

        return view($this->avisosView('index'), compact('avisos', 'estatisticas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Aviso::class);

        return view($this->avisosView('create'), [
            'igrejasChurches' => $this->igrejasChurchesForForms(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Aviso::class);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'conteudo' => 'nullable|string',
            'tipo' => 'required|in:info,success,warning,danger,promocao,novidade,anuncio',
            'posicao' => 'required|in:topo,meio,rodape,flutuante',
            'estilo' => 'required|in:banner,announcement,cta,modal,toast',
            'cor_primaria' => 'nullable|string|max:50',
            'cor_secundaria' => 'nullable|string|max:50',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url_acao' => 'nullable|url|max:500',
            'texto_botao' => 'nullable|string|max:100',
            'botao_exibir' => 'boolean',
            'dismissivel' => 'boolean',
            'ativo' => 'boolean',
            'destacar' => 'boolean',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'ordem' => 'nullable|integer|min:0',
            'configuracoes' => 'nullable|array',
            'church_ids' => 'nullable|array',
            'church_ids.*' => 'integer|exists:igrejas_churches,id',
        ]);

        // Upload de imagem
        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('avisos', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated = $this->mergeChurchIdsFromRequest($request, $validated);

        $aviso = Aviso::create($validated);

        return redirect()
            ->route($this->avisosRoute('show'), $aviso)
            ->with('success', 'Aviso criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aviso $aviso)
    {
        $this->authorize('view', $aviso);

        $aviso->load('usuario');

        // Estatísticas do aviso
        $diasRestantes = null;
        if ($aviso->data_fim) {
            $diferenca = now()->diffInDays($aviso->data_fim, false);
            // Se a diferença for negativa, já expirou
            if ($diferenca < 0) {
                $diasRestantes = 0;
            } else {
                // Arredondar para baixo para mostrar apenas dias completos
                $diasRestantes = floor($diferenca);
            }
        }

        $estatisticas = [
            'visualizacoes' => $aviso->visualizacoes,
            'cliques' => $aviso->cliques,
            'taxa_clique' => $aviso->visualizacoes > 0
                ? number_format(round(($aviso->cliques / $aviso->visualizacoes) * 100, 2), 2, ',', '.')
                : '0,00',
            'esta_ativo' => $aviso->estaAtivo(),
            'dias_restantes' => $diasRestantes,
        ];

        return view($this->avisosView('show'), compact('aviso', 'estatisticas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aviso $aviso)
    {
        $this->authorize('update', $aviso);

        return view($this->avisosView('edit'), [
            'aviso' => $aviso,
            'igrejasChurches' => $this->igrejasChurchesForForms(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aviso $aviso)
    {
        $this->authorize('update', $aviso);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'conteudo' => 'nullable|string',
            'tipo' => 'required|in:info,success,warning,danger,promocao,novidade,anuncio',
            'posicao' => 'required|in:topo,meio,rodape,flutuante',
            'estilo' => 'required|in:banner,announcement,cta,modal,toast',
            'cor_primaria' => 'nullable|string|max:50',
            'cor_secundaria' => 'nullable|string|max:50',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'url_acao' => 'nullable|url|max:500',
            'texto_botao' => 'nullable|string|max:100',
            'botao_exibir' => 'boolean',
            'dismissivel' => 'boolean',
            'ativo' => 'boolean',
            'destacar' => 'boolean',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'ordem' => 'nullable|integer|min:0',
            'configuracoes' => 'nullable|array',
            'remover_imagem' => 'boolean',
            'church_ids' => 'nullable|array',
            'church_ids.*' => 'integer|exists:igrejas_churches,id',
        ]);

        // Remover imagem se solicitado
        if ($request->boolean('remover_imagem') && $aviso->imagem) {
            Storage::disk('public')->delete($aviso->imagem);
            $validated['imagem'] = null;
        }

        // Upload de nova imagem
        if ($request->hasFile('imagem')) {
            // Remove imagem antiga
            if ($aviso->imagem) {
                Storage::disk('public')->delete($aviso->imagem);
            }
            $validated['imagem'] = $request->file('imagem')->store('avisos', 'public');
        } else {
            unset($validated['imagem']);
        }

        $validated = $this->mergeChurchIdsFromRequest($request, $validated);

        $aviso->update($validated);

        return redirect()
            ->route($this->avisosRoute('show'), $aviso)
            ->with('success', 'Aviso atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aviso $aviso)
    {
        $this->authorize('delete', $aviso);

        // Remove imagem se existir
        if ($aviso->imagem) {
            Storage::disk('public')->delete($aviso->imagem);
        }

        $aviso->delete();

        return redirect()
            ->route($this->avisosRoute('index'))
            ->with('success', 'Aviso excluído com sucesso!');
    }

    /**
     * Toggle ativo/inativo
     */
    public function toggleAtivo(Aviso $aviso)
    {
        $this->authorize('update', $aviso);

        $aviso->update(['ativo' => ! $aviso->ativo]);

        return back()->with('success',
            $aviso->ativo ? 'Aviso ativado com sucesso!' : 'Aviso desativado com sucesso!'
        );
    }
}
