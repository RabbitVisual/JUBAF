<?php

namespace Modules\PainelDiretoria\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProfileSensitiveDataRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProfileDataChangeRequestController extends Controller
{
    public function index()
    {
        $this->ensureDiretoriaExecutive();

        $pending = ProfileSensitiveDataRequest::query()
            ->with(['user.roles', 'user.church'])
            ->where('status', ProfileSensitiveDataRequest::STATUS_PENDING)
            ->orderByDesc('created_at')
            ->get();

        $history = ProfileSensitiveDataRequest::query()
            ->with(['user', 'reviewedBy'])
            ->whereIn('status', [ProfileSensitiveDataRequest::STATUS_APPROVED, ProfileSensitiveDataRequest::STATUS_REJECTED])
            ->orderByDesc('reviewed_at')
            ->orderByDesc('updated_at')
            ->limit(40)
            ->get();

        return view('paineldiretoria::profile.data-change-requests.index', compact('pending', 'history'));
    }

    public function approve(Request $request, ProfileSensitiveDataRequest $profileSensitiveDataRequest)
    {
        $this->ensureDiretoriaExecutive();

        $validated = $request->validate([
            'reviewer_note' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $profileSensitiveDataRequest->isPending()) {
            return back()->with('error', 'Este pedido já foi tratado.');
        }

        /** @var User $subject */
        $subject = $profileSensitiveDataRequest->user;
        $field = $profileSensitiveDataRequest->field;
        $value = $profileSensitiveDataRequest->requested_value;

        if ($field === ProfileSensitiveDataRequest::FIELD_EMAIL) {
            $exists = User::query()->where('email', $value)->where('id', '!=', $subject->id)->exists();
            if ($exists) {
                return back()->with('error', 'Este e-mail já está em uso por outro utilizador.');
            }
        }

        if ($field === ProfileSensitiveDataRequest::FIELD_CPF) {
            $cpf = preg_replace('/\D/', '', (string) $value) ?? '';
            if (strlen($cpf) !== 11) {
                return back()->with('error', 'CPF inválido no pedido.');
            }
            $exists = User::query()->where('cpf', $cpf)->where('id', '!=', $subject->id)->exists();
            if ($exists) {
                return back()->with('error', 'Este CPF já está em uso por outro utilizador.');
            }
            $value = $cpf;
        }

        DB::transaction(function () use ($profileSensitiveDataRequest, $subject, $field, $value, $validated) {
            if ($field === ProfileSensitiveDataRequest::FIELD_EMAIL) {
                $subject->email = $value;
            } else {
                $subject->cpf = $value;
            }
            $subject->save();

            $profileSensitiveDataRequest->update([
                'status' => ProfileSensitiveDataRequest::STATUS_APPROVED,
                'reviewed_by_user_id' => auth()->id(),
                'reviewed_at' => now(),
                'reviewer_note' => $validated['reviewer_note'] ?? null,
            ]);
        });

        return back()->with('success', 'Pedido aprovado e dados atualizados.');
    }

    public function reject(Request $request, ProfileSensitiveDataRequest $profileSensitiveDataRequest)
    {
        $this->ensureDiretoriaExecutive();

        $validated = $request->validate([
            'reviewer_note' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $profileSensitiveDataRequest->isPending()) {
            return back()->with('error', 'Este pedido já foi tratado.');
        }

        $profileSensitiveDataRequest->update([
            'status' => ProfileSensitiveDataRequest::STATUS_REJECTED,
            'reviewed_by_user_id' => auth()->id(),
            'reviewed_at' => now(),
            'reviewer_note' => $validated['reviewer_note'] ?? null,
        ]);

        return back()->with('success', 'Pedido recusado.');
    }

    protected function ensureDiretoriaExecutive(): void
    {
        if (user_is_diretoria_executive() || user_can_access_admin_panel()) {
            return;
        }

        abort(403);
    }
}
