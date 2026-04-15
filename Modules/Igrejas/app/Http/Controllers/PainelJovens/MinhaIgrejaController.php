<?php

namespace Modules\Igrejas\App\Http\Controllers\PainelJovens;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Igrejas\App\Models\Church;

class MinhaIgrejaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_unless($user->hasRole('jovens'), 403);

        $church = $user->church;
        if ($church) {
            $this->authorize('view', $church);
            $church->loadCount(['jovensMembers', 'leaders']);
        }

        $leaders = $church
            ? User::query()
                ->role('lider')
                ->where('church_id', $church->id)
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'phone'])
            : collect();

        return view('igrejas::paineljovens.minha-igreja.index', [
            'user' => $user,
            'church' => $church,
            'leaders' => $leaders,
        ]);
    }
}
