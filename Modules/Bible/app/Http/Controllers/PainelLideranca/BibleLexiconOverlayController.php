<?php

namespace Modules\Bible\App\Http\Controllers\PainelLideranca;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Bible\App\Models\BibleStrongsLexicon;

/**
 * Overlay editorial PT (lemma_br, notas) — sem alterar descrição técnica importada.
 */
class BibleLexiconOverlayController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $query = BibleStrongsLexicon::query()->orderBy('strong_number');

        if ($q !== '') {
            $upper = strtoupper($q);
            $query->where(function ($sub) use ($q, $upper) {
                $sub->where('strong_number', 'like', '%'.$upper.'%')
                    ->orWhere('lemma', 'like', '%'.$q.'%')
                    ->orWhere('lemma_br', 'like', '%'.$q.'%');
            });
        }

        $entries = $query->paginate(40)->withQueryString();

        return view('painellideranca::bible.lexicon.index', compact('entries', 'q'));
    }

    public function edit(string $strong_number)
    {
        $entry = BibleStrongsLexicon::query()
            ->where('strong_number', strtoupper($strong_number))
            ->firstOrFail();

        return view('painellideranca::bible.lexicon.edit', compact('entry'));
    }

    public function update(Request $request, string $strong_number)
    {
        $entry = BibleStrongsLexicon::query()
            ->where('strong_number', strtoupper($strong_number))
            ->firstOrFail();

        $validated = $request->validate([
            'lemma_br' => ['nullable', 'string', 'max:65535'],
            'semantic_equivalent_pt' => ['nullable', 'string', 'max:65535'],
            'meaning_usage_pt' => ['nullable', 'string', 'max:65535'],
        ]);

        $entry->update($validated);

        return redirect()
            ->route('painel.lideranca.bible.study.lexicon.edit', $entry->strong_number)
            ->with('success', 'Campos editoriais (PT) actualizados.');
    }
}
