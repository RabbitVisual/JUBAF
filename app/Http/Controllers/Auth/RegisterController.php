<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'cpf' => 'nullable|string|size:11|unique:users,cpf',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active' => true,
        ];

        // Adicionar CPF se fornecido (remover máscara)
        if ($request->filled('cpf')) {
            $userData['cpf'] = preg_replace('/[^0-9]/', '', $request->cpf);
        }

        $user = User::create($userData);

        $user->assignRole('lider');

        Auth::login($user);

        return redirect()->route('lideres.dashboard');
    }
}

