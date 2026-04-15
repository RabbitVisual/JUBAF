<?php

namespace Modules\PainelJovens\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('church');

        return view('paineljovens::dashboard', [
            'user' => $user,
        ]);
    }
}
