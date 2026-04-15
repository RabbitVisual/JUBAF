<?php

namespace Modules\Bible\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Bible\App\Models\BibleCrossReference;

class BibleCrossReferenceAdminController extends Controller
{
    use Concerns\ResolvesBibleAdminContext;

    public function index(Request $request)
    {
        $fromBook = $request->query('from_book_number');
        $fromChapter = $request->query('from_chapter');

        $query = BibleCrossReference::query()
            ->orderBy('from_book_number')
            ->orderBy('from_chapter')
            ->orderBy('from_verse')
            ->orderBy('weight');

        if ($fromBook !== null && $fromBook !== '') {
            $query->where('from_book_number', (int) $fromBook);
        }
        if ($fromChapter !== null && $fromChapter !== '') {
            $query->where('from_chapter', (int) $fromChapter);
        }

        $rows = $query->paginate(50)->withQueryString();

        return view($this->bibleView('study.cross-refs.index'), compact('rows', 'fromBook', 'fromChapter'));
    }

    public function edit(int $id)
    {
        $row = BibleCrossReference::query()->findOrFail($id);

        return view($this->bibleView('study.cross-refs.edit'), compact('row'));
    }

    public function update(Request $request, int $id)
    {
        $row = BibleCrossReference::query()->findOrFail($id);

        $validated = $request->validate([
            'testament' => ['required', 'string', 'max:8'],
            'from_book_number' => ['required', 'integer', 'min:1', 'max:66'],
            'from_chapter' => ['required', 'integer', 'min:1'],
            'from_verse' => ['required', 'integer', 'min:1'],
            'to_book_number' => ['required', 'integer', 'min:1', 'max:66'],
            'to_chapter' => ['required', 'integer', 'min:1'],
            'to_verse' => ['required', 'integer', 'min:1'],
            'kind' => ['nullable', 'string', 'max:32'],
            'weight' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'source_slug' => ['nullable', 'string', 'max:64'],
            'note_pt' => ['nullable', 'string', 'max:512'],
        ]);

        $validated['weight'] = (int) ($validated['weight'] ?? 0);

        $row->update($validated);

        return redirect()
            ->to($this->bibleRoute('study.cross-refs.edit', $row->id))
            ->with('success', 'Referência cruzada actualizada.');
    }

    public function destroy(int $id)
    {
        BibleCrossReference::query()->whereKey($id)->delete();

        return redirect()
            ->to($this->bibleRoute('study.cross-refs.index'))
            ->with('success', 'Referência removida.');
    }
}
