<?php

namespace Modules\PainelDiretoria\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserProfileMediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DiretoriaProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->load(['roles', 'permissions', 'church', 'profilePhotos']);

        return view('paineldiretoria::profile.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($request->has('cpf')) {
            $rawCpf = $request->input('cpf');
            $request->merge([
                'cpf' => ($rawCpf === null || $rawCpf === '') ? null : preg_replace('/\D/', '', (string) $rawCpf),
            ]);
        }

        $validated = $request->validate(array_merge([
            'first_name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'cpf' => ['nullable', 'string', 'size:11', Rule::unique('users', 'cpf')->ignore($user->id)],
            'phone' => 'nullable|string|max:32',
            'church_phone' => 'nullable|string|max:32',
            'birth_date' => 'nullable|date',
            'emergency_contact_name' => 'nullable|string|max:120',
            'emergency_contact_phone' => 'nullable|string|max:32',
            'emergency_contact_relationship' => 'nullable|string|max:80',
            'password' => 'nullable|string|min:8|confirmed',
        ], UserProfileMediaService::validationRules($user->profilePhotos()->count())));

        try {
            unset($validated['cover_photo'], $validated['profile_photos'], $validated['photo'], $validated['cover_position_x'], $validated['cover_position_y']);

            if (isset($validated['password']) && ! empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            app(UserProfileMediaService::class)->handleProfileFormUploads($user->fresh(), $request);

            return redirect()->route('diretoria.profile')
                ->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar perfil: '.$e->getMessage());
        }
    }
}
