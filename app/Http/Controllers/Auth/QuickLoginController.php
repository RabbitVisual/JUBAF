<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuickLoginController extends Controller
{
    /**
     * Quick login for demo users (only in development).
     */
    public function quickLogin(Request $request)
    {
        if (! app()->environment('local', 'development', 'dev')) {
            abort(403, 'Quick login is only available in development mode.');
        }

        $accounts = collect(config('dev_demo_users.accounts', []));
        $allowedSlugs = $accounts->pluck('slug')->filter()->all();

        $slug = $request->input('role');
        if ($slug === null && $request->has('type')) {
            $slug = match ($request->input('type')) {
                'admin' => 'admin',
                'member' => 'membro',
                default => null,
            };
        }

        if (! $slug || ! in_array($slug, $allowedSlugs, true)) {
            return back()->withErrors(['email' => 'Perfil de demonstração inválido.']);
        }

        $row = $accounts->firstWhere('slug', $slug);
        $email = $row['email'] ?? null;

        if (! $email) {
            return back()->withErrors(['email' => 'Conta demo mal configurada.']);
        }

        $user = User::with('role')->where('email', $email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Usuário demo não encontrado. Execute: php artisan db:seed --class=DemoUsersSeeder',
            ]);
        }

        Auth::login($user, $request->boolean('remember', true));

        $request->session()->regenerate();

        if ($user->role && $user->role->slug === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('memberpanel.dashboard'));
    }
}
