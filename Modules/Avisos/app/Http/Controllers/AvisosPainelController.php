<?php

namespace Modules\Avisos\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Avisos\App\Models\Aviso;

class AvisosPainelController extends Controller
{
    public function index(Request $request)
    {
        $query = Aviso::query()
            ->ativos()
            ->forAudience($request->user())
            ->with('usuario')
            ->orderBy('destacar', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('titulo', 'like', '%'.$q.'%')
                    ->orWhere('descricao', 'like', '%'.$q.'%');
            });
        }

        $avisos = $query->paginate(12)->withQueryString();

        $view = match (true) {
            $request->routeIs('jovens.*') => 'avisos::paineljovens.avisos.index',
            $request->routeIs('lideres.*') => 'avisos::painellider.avisos.index',
            $request->routeIs('pastor.*') => 'avisos::pastor.index',
            default => abort(404),
        };

        return view($view, compact('avisos'));
    }

    public function show(Request $request, Aviso $aviso)
    {
        $aviso = Aviso::query()
            ->ativos()
            ->forAudience($request->user())
            ->whereKey($aviso->getKey())
            ->with('usuario')
            ->firstOrFail();

        $view = match (true) {
            $request->routeIs('jovens.*') => 'avisos::paineljovens.avisos.show',
            $request->routeIs('lideres.*') => 'avisos::painellider.avisos.show',
            $request->routeIs('pastor.*') => 'avisos::pastor.show',
            default => abort(404),
        };

        return view($view, compact('aviso'));
    }
}
