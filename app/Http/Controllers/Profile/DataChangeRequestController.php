<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\ProfileSensitiveDataRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DataChangeRequestController extends Controller
{
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAnyRole(['jovens', 'lider', 'pastor'])) {
            abort(403);
        }

        $validated = $request->validate([
            'field' => ['required', 'string', Rule::in([ProfileSensitiveDataRequest::FIELD_EMAIL, ProfileSensitiveDataRequest::FIELD_CPF])],
            'requested_value' => ['required', 'string', 'max:255'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $field = $validated['field'];
        $raw = trim((string) $validated['requested_value']);

        if ($field === ProfileSensitiveDataRequest::FIELD_EMAIL) {
            $request->validate(['requested_value' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id]]);
            $normalized = strtolower($raw);
        } else {
            $normalized = preg_replace('/\D/', '', $raw) ?? '';
            if (strlen($normalized) !== 11) {
                return back()->withErrors(['requested_value' => 'Indique um CPF válido (11 dígitos).'])->withInput();
            }
            $cpfTaken = User::query()->where('cpf', $normalized)->where('id', '!=', $user->id)->exists();
            if ($cpfTaken) {
                return back()->withErrors(['requested_value' => 'Este CPF já está registado no sistema.'])->withInput();
            }
        }

        $current = $field === ProfileSensitiveDataRequest::FIELD_EMAIL
            ? strtolower((string) $user->email)
            : (string) preg_replace('/\D/', '', (string) $user->cpf);

        if ($normalized === $current) {
            return back()->withErrors(['requested_value' => 'O valor indicado é igual ao atual.'])->withInput();
        }

        if ($user->hasPendingProfileSensitiveRequest($field)) {
            return back()->with('error', 'Já existe um pedido pendente para este campo. Aguarde a análise da diretoria.');
        }

        ProfileSensitiveDataRequest::query()->create([
            'user_id' => $user->id,
            'field' => $field,
            'previous_value' => $field === ProfileSensitiveDataRequest::FIELD_EMAIL ? $user->email : $user->cpf,
            'requested_value' => $normalized,
            'reason' => $validated['reason'] ?? null,
            'status' => ProfileSensitiveDataRequest::STATUS_PENDING,
        ]);

        $routeName = $user->hasRole('jovens') ? 'jovens.profile.index' : 'lideres.profile.index';

        return redirect()->route($routeName)
            ->with('success', 'Pedido enviado à diretoria. Será analisado em breve.');
    }
}
