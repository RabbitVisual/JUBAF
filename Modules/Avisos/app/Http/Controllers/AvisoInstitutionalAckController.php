<?php

namespace Modules\Avisos\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Avisos\App\Models\Aviso;
use Modules\Avisos\App\Services\AvisoService;

class AvisoInstitutionalAckController extends Controller
{
    public function __invoke(Request $request, Aviso $aviso, AvisoService $avisoService)
    {
        $user = $request->user();
        $user->loadMissing('roles');

        $ok = Aviso::query()
            ->whereKey($aviso->getKey())
            ->modoQuadro()
            ->ativos()
            ->forAudience($user)
            ->forTargetRole($user)
            ->exists();

        if (! $ok) {
            abort(403);
        }

        $avisoService->marcarCiente($user, $aviso);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Registado como ciente.');
    }
}
