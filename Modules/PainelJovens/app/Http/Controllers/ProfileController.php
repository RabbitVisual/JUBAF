<?php

namespace Modules\PainelJovens\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserProfileMediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Modules\PainelJovens\App\Models\JovemPerfil;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load(['roles', 'permissions', 'church', 'talentProfile.skills', 'jovemPerfil', 'profilePhotos']);

        return view('paineljovens::profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate(array_merge([
            'first_name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'phone' => 'nullable|string|max:32',
            'church_phone' => 'nullable|string|max:32',
            'birth_date' => 'nullable|date',
            'emergency_contact_name' => 'nullable|string|max:120',
            'emergency_contact_phone' => 'nullable|string|max:32',
            'emergency_contact_relationship' => 'nullable|string|max:80',
            'current_password' => 'nullable|required_with:password',
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'marital_status' => 'nullable|string|max:48',
            'profession' => 'nullable|string|max:160',
            'census_bio' => 'nullable|string|max:2000',
            'social_instagram' => 'nullable|string|max:120',
            'social_youtube' => 'nullable|string|max:255',
            'social_outro' => 'nullable|string|max:255',
        ], UserProfileMediaService::validationRules($user->profilePhotos()->count())));

        if ($request->filled('password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'A palavra-passe atual está incorreta.'])->withInput();
            }
        }

        unset($validated['cover_photo'], $validated['profile_photos'], $validated['photo'], $validated['cover_position_x'], $validated['cover_position_y']);

        $user->fill([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'church_phone' => $validated['church_phone'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
            'emergency_contact_relationship' => $validated['emergency_contact_relationship'] ?? null,
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $this->syncJovemPerfil($user->fresh(), [
            'marital_status' => $validated['marital_status'] ?? null,
            'profession' => $validated['profession'] ?? null,
            'census_bio' => $validated['census_bio'] ?? null,
            'social_instagram' => $validated['social_instagram'] ?? null,
            'social_youtube' => $validated['social_youtube'] ?? null,
            'social_outro' => $validated['social_outro'] ?? null,
        ]);

        app(UserProfileMediaService::class)->handleProfileFormUploads($user->fresh(), $request);

        return redirect()->route('jovens.profile.index')
            ->with('success', 'Perfil atualizado com sucesso.');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function syncJovemPerfil(\App\Models\User $user, array $data): void
    {
        $links = array_filter([
            'instagram' => $data['social_instagram'] ?? null,
            'youtube' => $data['social_youtube'] ?? null,
            'outro' => $data['social_outro'] ?? null,
        ], fn ($v) => filled($v));

        JovemPerfil::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'marital_status' => $data['marital_status'] ?? null,
                'profession' => $data['profession'] ?? null,
                'census_bio' => $data['census_bio'] ?? null,
                'social_links' => $links === [] ? null : $links,
            ]
        );
    }
}
