<?php

namespace Modules\PainelLider\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('church');
        $jovensCount = 0;
        if ($user->church_id) {
            $jovensCount = User::query()->role('jovens')->where('church_id', $user->church_id)->count();
        }

        return view('painellider::dashboard', [
            'user' => $user,
            'jovensCount' => $jovensCount,
        ]);
    }

    public function filtros()
    {
        return response()->json([]);
    }

    public function estatisticas()
    {
        return response()->json([]);
    }
}
