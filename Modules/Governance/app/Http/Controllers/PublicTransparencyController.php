<?php

namespace Modules\Governance\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Governance\Models\Minute;
use Modules\Governance\Models\OfficialCommunication;

class PublicTransparencyController extends Controller
{
    public function index()
    {
        $minutes = Minute::query()->published()->with('assembly')->orderByDesc('published_at')->paginate(12, ['*'], 'atas');
        $communications = OfficialCommunication::query()->published()->orderByDesc('published_at')->paginate(12, ['*'], 'comms');

        return view('governance::public.index', compact('minutes', 'communications'));
    }

    public function showMinute(Minute $minute)
    {
        abort_unless($minute->isPublished(), 404);
        $minute->load('assembly');

        return view('governance::public.minute', compact('minute'));
    }

    public function showCommunication(OfficialCommunication $communication)
    {
        abort_unless($communication->is_published && $communication->published_at, 404);

        return view('governance::public.communication', compact('communication'));
    }
}
