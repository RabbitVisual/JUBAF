<?php

namespace Modules\Avisos\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Avisos\App\Models\Aviso;

class AvisosController extends Controller
{
    /**
     * Listagem pública de avisos ativos para a audiência do visitante.
     */
    public function index()
    {
        $base = Aviso::query()
            ->ativos()
            ->forAudience(auth()->user());

        $avisos = (clone $base)
            ->with('usuario')
            ->orderBy('destacar', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $feedUpdatedAt = (clone $base)->max('updated_at');

        return view('avisos::public.index', [
            'avisos' => $avisos,
            'feedUpdatedAt' => $feedUpdatedAt ? \Carbon\Carbon::parse($feedUpdatedAt)->toIso8601String() : null,
        ]);
    }

    /**
     * Detalhe público de um aviso (respeita audiência e vigência).
     */
    public function show(Aviso $aviso)
    {
        $aviso = Aviso::query()
            ->ativos()
            ->forAudience(auth()->user())
            ->whereKey($aviso->getKey())
            ->with('usuario')
            ->firstOrFail();

        return view('avisos::public.show', compact('aviso'));
    }
}
