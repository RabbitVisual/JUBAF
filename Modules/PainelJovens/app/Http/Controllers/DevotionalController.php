<?php

namespace Modules\PainelJovens\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Devotional;
use Illuminate\View\View;

class DevotionalController extends Controller
{
    public function index(): View
    {
        $rows = Devotional::query()->publishedOrdered()->paginate(12);

        $pageTitle = 'Devocionais';
        $pageLead = 'Reflexões espirituais publicadas pela JUBAF — lê aqui no teu painel Unijovem.';

        return view('paineljovens::devotionals.index', compact('rows', 'pageTitle', 'pageLead'));
    }

    public function show(Devotional $devotional): View
    {
        if ($devotional->status !== Devotional::STATUS_PUBLISHED || $devotional->published_at === null) {
            abort(404);
        }

        return view('paineljovens::devotionals.show', compact('devotional'));
    }
}
