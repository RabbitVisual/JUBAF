<?php

namespace Modules\Igrejas\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Services\CepLookupService;

class CepLookupController extends Controller
{
    public function show(Request $request, CepLookupService $cepLookup): JsonResponse
    {
        $this->authorize('viewAny', Church::class);

        $request->validate([
            'cep' => ['required', 'string', 'min:8', 'max:12'],
        ]);

        $result = $cepLookup->lookup($request->string('cep')->toString());

        if (! $result['ok']) {
            return response()->json(['message' => $result['error'] ?? 'CEP inválido.'], 422);
        }

        return response()->json($result['data']);
    }
}
