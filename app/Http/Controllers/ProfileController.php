<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Validação base
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if (user_can_access_admin_panel($user)) {
            $rules['cpf'] = 'nullable|string|max:14|unique:users,cpf,' . $user->id;
        }

        $validated = $request->validate($rules);

        try {
            // Upload da foto
            if ($request->hasFile('photo')) {
                // Deletar foto antiga se existir
                if ($user->photo && file_exists(public_path('storage/' . $user->photo))) {
                    unlink(public_path('storage/' . $user->photo));
                }

                $photo = $request->file('photo');
                $photoName = 'profile_' . $user->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('profiles', $photoName, 'public');
                $validated['photo'] = $photoPath;
            }

            if (user_can_access_admin_panel($user) && isset($validated['cpf']) && ! empty($validated['cpf'])) {
                $validated['cpf'] = preg_replace('/[^0-9]/', '', $validated['cpf']);
            } else {
                unset($validated['cpf']);
            }

            // Atualizar senha se fornecida
            if (isset($validated['password']) && !empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return redirect()->route('profile')
                ->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar perfil: ' . $e->getMessage());
        }
    }
}
