<?php

namespace Modules\Secretaria\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Secretaria\App\Models\Convocation;
use Modules\Secretaria\App\Models\Minute;

class PublicSecretariaController extends Controller
{
    public function index()
    {
        $minutes = Minute::query()->where('status', 'published')->orderByDesc('published_at')->limit(20)->get(['id', 'title', 'published_at']);
        $convocations = Convocation::query()->where('status', 'published')->where('assembly_at', '>=', now()->subDays(1))->orderBy('assembly_at')->limit(10)->get(['id', 'title', 'assembly_at']);

        return view('secretaria::public.index', compact('minutes', 'convocations'));
    }
}
