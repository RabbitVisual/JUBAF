<?php

namespace Modules\Bible\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Bible\App\Models\BibleCommentaryEntry;
use Modules\Bible\App\Models\BibleCommentarySource;

class BibleCommentaryAdminController extends Controller
{
    use Concerns\ResolvesBibleAdminContext;

    public function sourcesIndex()
    {
        $sources = BibleCommentarySource::query()
            ->withCount('entries')
            ->orderBy('title')
            ->paginate(30)
            ->withQueryString();

        return view($this->bibleView('study.commentary.sources-index'), compact('sources'));
    }

    public function sourcesEdit(int $id)
    {
        $source = BibleCommentarySource::query()->findOrFail($id);

        return view($this->bibleView('study.commentary.sources-edit'), compact('source'));
    }

    public function sourcesUpdate(Request $request, int $id)
    {
        $source = BibleCommentarySource::query()->findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:8'],
            'license_note' => ['nullable', 'string', 'max:65535'],
            'url_template' => ['nullable', 'string', 'max:512'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $source->update($validated);

        return redirect()
            ->to($this->bibleRoute('study.commentary.sources.edit', $source->id))
            ->with('success', 'Fonte de comentário actualizada.');
    }

    public function entriesIndex(Request $request)
    {
        $book = $request->query('book_number');

        $query = BibleCommentaryEntry::query()
            ->with('source')
            ->orderByDesc('id');

        if ($book !== null && $book !== '') {
            $query->where('book_number', (int) $book);
        }

        $entries = $query->paginate(40)->withQueryString();

        return view($this->bibleView('study.commentary.entries-index'), compact('entries', 'book'));
    }

    public function entriesEdit(int $id)
    {
        $entry = BibleCommentaryEntry::query()->with('source')->findOrFail($id);

        return view($this->bibleView('study.commentary.entries-edit'), compact('entry'));
    }

    public function entriesUpdate(Request $request, int $id)
    {
        $entry = BibleCommentaryEntry::query()->findOrFail($id);

        $validated = $request->validate([
            'book_number' => ['required', 'integer', 'min:1', 'max:66'],
            'chapter_from' => ['required', 'integer', 'min:1'],
            'verse_from' => ['required', 'integer', 'min:1'],
            'chapter_to' => ['required', 'integer', 'min:1'],
            'verse_to' => ['required', 'integer', 'min:1'],
            'body' => ['required', 'string', 'max:65535'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $entry->update($validated);

        return redirect()
            ->to($this->bibleRoute('study.commentary.entries.edit', $entry->id))
            ->with('success', 'Entrada de comentário actualizada.');
    }
}
