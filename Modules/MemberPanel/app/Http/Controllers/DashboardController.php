<?php

namespace Modules\MemberPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the member dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $user->load(['role', 'church']);

        $stats = [
            'profile_completion' => $user->getProfileCompletionPercentage(),
            'church_label' => $user->church?->name,
        ];

        return view('memberpanel::dashboard', compact('user', 'stats'));
    }

    // Mantido sem o método calculateProfileCompletion(): o cálculo oficial
    // de completude agora vem sempre de User::getProfileCompletionPercentage().
}
