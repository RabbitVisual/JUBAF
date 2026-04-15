<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\UserProfilePhoto;
use App\Services\UserProfileMediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserProfilePhotoController extends Controller
{
    public function activate(Request $request, UserProfilePhoto $photo): RedirectResponse
    {
        $user = $request->user();
        abort_unless($photo->user_id === $user->id, 403);

        app(UserProfileMediaService::class)->setActivePhoto($user, $photo);

        return back()->with('success', 'Foto principal atualizada.');
    }

    public function destroy(Request $request, UserProfilePhoto $photo): RedirectResponse
    {
        $user = $request->user();
        abort_unless($photo->user_id === $user->id, 403);

        app(UserProfileMediaService::class)->deletePhoto($user, $photo);

        return back()->with('success', 'Foto removida.');
    }
}
