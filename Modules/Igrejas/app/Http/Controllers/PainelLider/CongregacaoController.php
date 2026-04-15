<?php

namespace Modules\Igrejas\App\Http\Controllers\PainelLider;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Igrejas\App\Models\Church;

class CongregacaoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_unless($user->hasRole('lider'), 403);

        $church = $user->church;
        if ($church) {
            $this->authorize('view', $church);
            $church->loadCount(['users', 'jovensMembers', 'leaders']);
        }

        $jovens = User::query()
            ->role('jovens')
            ->where('church_id', $user->church_id)
            ->orderBy('name')
            ->paginate(20);

        $coLeaders = $church
            ? User::query()
                ->role('lider')
                ->where('church_id', $church->id)
                ->where('id', '!=', $user->id)
                ->orderBy('name')
                ->get()
            : collect();

        return view('igrejas::painellider.congregacao.index', [
            'user' => $user,
            'church' => $church,
            'jovens' => $jovens,
            'coLeaders' => $coLeaders,
        ]);
    }
}
