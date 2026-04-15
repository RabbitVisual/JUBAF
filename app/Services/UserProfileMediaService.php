<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfilePhoto;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserProfileMediaService
{
    public const MAX_PROFILE_PHOTOS = 3;

    /**
     * @return array<string, mixed>
     */
    public static function validationRules(int $existingPhotoCount): array
    {
        $remaining = max(0, self::MAX_PROFILE_PHOTOS - $existingPhotoCount);

        return [
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'cover_position_x' => 'nullable|integer|min:0|max:100',
            'cover_position_y' => 'nullable|integer|min:0|max:100',
            'profile_photos' => 'nullable|array|max:'.$remaining,
            'profile_photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
        ];
    }

    public function handleProfileFormUploads(User $user, Request $request): void
    {
        $disk = Storage::disk('public');

        $uploadedNewCover = $request->hasFile('cover_photo');

        if ($uploadedNewCover) {
            if ($user->cover_photo && $disk->exists($user->cover_photo)) {
                $disk->delete($user->cover_photo);
            }
            $user->cover_photo = $request->file('cover_photo')->store('profile-covers', 'public');
        }

        if ($request->has(['cover_position_x', 'cover_position_y'])) {
            $user->cover_position_x = max(0, min(100, (int) $request->input('cover_position_x', 50)));
            $user->cover_position_y = max(0, min(100, (int) $request->input('cover_position_y', 50)));
        } elseif ($uploadedNewCover) {
            $user->cover_position_x = 50;
            $user->cover_position_y = 50;
        }

        if ($uploadedNewCover || ($user->isDirty(['cover_position_x', 'cover_position_y', 'cover_photo']))) {
            $user->saveQuietly();
        }

        if ($request->hasFile('profile_photos')) {
            $files = array_values(array_filter($request->file('profile_photos', [])));
            $slots = self::MAX_PROFILE_PHOTOS - $user->profilePhotos()->count();
            foreach (array_slice($files, 0, max(0, $slots)) as $file) {
                if ($file instanceof UploadedFile) {
                    $this->appendPhoto($user, $file);
                }
            }
        }

        if ($request->hasFile('photo')) {
            $this->handleLegacySinglePhoto($user, $request->file('photo'));
        }

        $this->ensureActiveAndSyncColumn($user->fresh());
        $user->touch();
    }

    protected function appendPhoto(User $user, UploadedFile $file): UserProfilePhoto
    {
        $path = $file->store('profiles', 'public');
        $count = $user->profilePhotos()->count();
        $makeActive = $count === 0;

        if ($makeActive) {
            $user->profilePhotos()->update(['is_active' => false]);
        }

        return $user->profilePhotos()->create([
            'path' => $path,
            'sort_order' => $count,
            'is_active' => $makeActive,
        ]);
    }

    protected function handleLegacySinglePhoto(User $user, UploadedFile $file): void
    {
        $path = $file->store('profiles', 'public');
        $disk = Storage::disk('public');
        $count = $user->profilePhotos()->count();

        if ($count < self::MAX_PROFILE_PHOTOS) {
            $user->profilePhotos()->update(['is_active' => false]);
            $sort = $user->profilePhotos()->max('sort_order');
            $user->profilePhotos()->create([
                'path' => $path,
                'sort_order' => is_numeric($sort) ? ((int) $sort + 1) : 0,
                'is_active' => true,
            ]);

            return;
        }

        $active = $user->profilePhotos()->where('is_active', true)->first();
        if ($active) {
            if ($disk->exists($active->path)) {
                $disk->delete($active->path);
            }
            $active->update(['path' => $path]);

            return;
        }

        $first = $user->profilePhotos()->orderBy('sort_order')->orderBy('id')->first();
        if ($first) {
            if ($disk->exists($first->path)) {
                $disk->delete($first->path);
            }
            $user->profilePhotos()->update(['is_active' => false]);
            $first->update(['path' => $path, 'is_active' => true]);
        }
    }

    public function ensureActiveAndSyncColumn(User $user): void
    {
        $user->load('profilePhotos');
        if ($user->profilePhotos->isEmpty()) {
            return;
        }

        $active = $user->profilePhotos->firstWhere('is_active', true);
        if (! $active) {
            $active = $user->profilePhotos->sortBy('sort_order')->first();
            $user->profilePhotos()->update(['is_active' => false]);
            if ($active) {
                $active->update(['is_active' => true]);
            }
        }

        $active = $user->profilePhotos()->where('is_active', true)->first();
        if ($active && $user->photo !== $active->path) {
            $user->photo = $active->path;
            $user->saveQuietly();
        }
    }

    public function setActivePhoto(User $user, UserProfilePhoto $photo): void
    {
        abort_unless($photo->user_id === $user->id, 403);
        $user->profilePhotos()->update(['is_active' => false]);
        $photo->update(['is_active' => true]);
        $user->photo = $photo->path;
        $user->saveQuietly();
        $user->touch();
    }

    public function deletePhoto(User $user, UserProfilePhoto $photo): void
    {
        abort_unless($photo->user_id === $user->id, 403);
        $disk = Storage::disk('public');
        $wasActive = $photo->is_active;
        $path = $photo->path;
        $photo->delete();

        if ($disk->exists($path)) {
            $disk->delete($path);
        }

        $user->refresh();
        $remaining = $user->profilePhotos()->orderBy('sort_order')->orderBy('id')->get();

        if ($remaining->isEmpty()) {
            $user->photo = null;
            $user->saveQuietly();
            $user->touch();

            return;
        }

        if ($wasActive) {
            $user->profilePhotos()->update(['is_active' => false]);
            $next = $remaining->first();
            $next->update(['is_active' => true]);
            $user->photo = $next->path;
            $user->saveQuietly();
        } else {
            $this->ensureActiveAndSyncColumn($user->fresh());
        }

        $user->touch();
    }
}
