<?php

use Illuminate\Support\Facades\Route;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\ChurchChangeRequest;
use Modules\Igrejas\App\Policies\ChurchPolicy;

Route::middleware('auth:sanctum')->prefix('igrejas')->group(function () {
    Route::get('/churches', function () {
        $user = request()->user();
        abort_unless($user, 403);

        $columns = ['id', 'name', 'slug', 'city', 'email', 'phone', 'sector', 'cooperation_status', 'foundation_date'];

        if (ChurchPolicy::canBrowseAllChurches($user)) {
            return Church::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get($columns);
        }

        if ($user->hasAnyRole(['lider', 'jovens', 'pastor'])) {
            $ids = $user->affiliatedChurchIds();
            if ($ids === []) {
                abort(403);
            }
            return Church::query()
                ->whereIn('id', $ids)
                ->where('is_active', true)
                ->orderBy('name')
                ->get($columns);
        }

        abort(403);
    })->name('igrejas.churches.index');

    Route::get('/change-requests', function () {
        $user = request()->user();
        abort_unless($user && $user->can('igrejas.requests.submit'), 403);

        return ChurchChangeRequest::query()
            ->where('submitted_by', $user->id)
            ->orderByDesc('updated_at')
            ->limit(100)
            ->get(['id', 'church_id', 'type', 'status', 'created_at', 'updated_at']);
    })->name('igrejas.change-requests.index');
});
