<?php

namespace Modules\Bible\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Bible\App\Models\BibleStrongsLexicon;

class BibleStrongsLexiconController extends Controller
{
    use Concerns\ResolvesBibleAdminContext;

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

        return view($this->bibleView('study.strongs.index'), compact('entries', 'q'));
    }

    public function edit(string $strong_number)
    {
        $entry = BibleStrongsLexicon::query()
            ->where('strong_number', strtoupper($strong_number))
            ->firstOrFail();

        return view($this->bibleView('study.strongs.edit'), compact('entry'));
    }

    public function update(Request $request, string $strong_number)
    {
        $entry = BibleStrongsLexicon::query()
            ->where('strong_number', strtoupper($strong_number))
            ->firstOrFail();

        $canEditTechnical = ! $entry->description_frozen || $request->boolean('allow_technical_edit');

        $baseRules = [
            'lemma_br' => ['nullable', 'string', 'max:65535'],
            'semantic_equivalent_pt' => ['nullable', 'string', 'max:65535'],
            'meaning_usage_pt' => ['nullable', 'string', 'max:65535'],
            'admin_locked' => ['sometimes', 'boolean'],
            'description_frozen' => ['sometimes', 'boolean'],
            'allow_technical_edit' => ['sometimes', 'boolean'],
            'restore_description_from_original' => ['sometimes', 'boolean'],
        ];

        $technicalRules = [
            'description' => ['nullable', 'string', 'max:65535'],
            'xlit' => ['nullable', 'string', 'max:65535'],
            'pronounce' => ['nullable', 'string', 'max:255'],
        ];

        $validated = $request->validate($canEditTechnical ? $baseRules + $technicalRules : $baseRules);

        $payload = [
            'lemma_br' => $validated['lemma_br'] ?? null,
            'semantic_equivalent_pt' => $validated['semantic_equivalent_pt'] ?? null,
            'meaning_usage_pt' => $validated['meaning_usage_pt'] ?? null,
            'admin_locked' => $request->boolean('admin_locked'),
            'description_frozen' => $request->boolean('description_frozen'),
        ];

        if ($canEditTechnical) {
            $payload['description'] = $validated['description'] ?? null;
            $payload['xlit'] = $validated['xlit'] ?? null;
            $payload['pronounce'] = $validated['pronounce'] ?? null;
        }

        if ($request->boolean('restore_description_from_original') && filled($entry->description_original)) {
            $payload['description'] = $entry->description_original;
        }

        $entry->update($payload);

        return redirect()
            ->to($this->bibleRoute('study.strongs.edit', $entry->strong_number))
            ->with('success', 'Entrada Strong actualizada.');
    }
}
