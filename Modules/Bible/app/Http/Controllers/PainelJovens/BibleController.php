<?php

namespace Modules\Bible\App\Http\Controllers\PainelJovens;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Bible\App\Models\Book;
use Modules\Bible\App\Models\BibleVersion;
use Modules\Bible\App\Models\Verse;

class BibleController extends Controller
{
    /** @var string Namespace das views (ex.: bible::paineljovens.bible) */
    protected string $bibleViewsNamespace = 'bible::paineljovens.bible';

    /** @var string Prefixo das rotas (member.bible.* no fluxo social; painel /jovens usa subclasse com jovens.bible.*) */
    protected string $bibleRoutesPrefix = 'member.bible';

    protected function bibleView(string $name, array $data = [])
    {
        return view($this->bibleViewsNamespace.'.'.$name, $data);
    }

    protected function bibleRedirect(string $routeSuffix, array $parameters = [], int $status = 302, array $headers = [])
    {
        return redirect()->route($this->bibleRoutesPrefix.'.'.$routeSuffix, $parameters, $status, $headers);
    }

    /**
     * Resolve book by numeric id (when linking from plan reader) or by book_number in the URL.
     */
    protected function resolveBookForVersion(BibleVersion $version, string|int $bookIdentifier): Book
    {
        if (is_numeric($bookIdentifier)) {
            $byId = $version->books()->where('id', (int) $bookIdentifier)->first();
            if ($byId) {
                return $byId;
            }
        }

        return $version->books()->where('book_number', $bookIdentifier)->firstOrFail();
    }

    public function index()
    {
        $defaultVersion = BibleVersion::default()->first()
            ?? BibleVersion::active()->first();

        if (! $defaultVersion) {
            return $this->bibleView('no-version');
        }

        return $this->bibleRedirect('plans.index');
    }

    public function read($versionAbbr = null)
    {
        if ($versionAbbr) {
            $version = BibleVersion::where('abbreviation', $versionAbbr)->firstOrFail();
        } else {
            $version = BibleVersion::default()->first() ?? BibleVersion::active()->first();
            if (! $version) {
                return $this->bibleView('no-version');
            }
        }

        $versions = BibleVersion::active()->orderBy('name')->get();
        $books = $version->books()->ordered()->get();
        $oldTestament = $books->where('testament', 'old');
        $newTestament = $books->where('testament', 'new');

        return $this->bibleView('read', compact('version', 'versions', 'oldTestament', 'newTestament'));
    }

    public function showBook($versionAbbr, $bookNumber)
    {
        $version = BibleVersion::where('abbreviation', $versionAbbr)->firstOrFail();
        $book = $this->resolveBookForVersion($version, $bookNumber);
        $chapters = $book->chapters()->orderBy('chapter_number')->get();

        return $this->bibleView('book', compact('version', 'book', 'chapters'));
    }

    public function showChapter($versionAbbr, $bookNumber, $chapterNumber)
    {
        $version = BibleVersion::where('abbreviation', $versionAbbr)->firstOrFail();
        $book = $this->resolveBookForVersion($version, $bookNumber);
        $chapter = $book->chapters()->where('chapter_number', $chapterNumber)->firstOrFail();
        $verses = $chapter->verses()->orderBy('verse_number')->get();

        $previousChapter = $book->chapters()
            ->where('chapter_number', '<', $chapterNumber)
            ->orderBy('chapter_number', 'desc')
            ->first();

        $nextChapter = $book->chapters()
            ->where('chapter_number', '>', $chapterNumber)
            ->orderBy('chapter_number')
            ->first();

        if (! $nextChapter) {
            $nextBook = $version->books()
                ->where('book_number', '>', $book->book_number)
                ->orderBy('book_number')
                ->first();
            if ($nextBook) {
                $nextChapter = $nextBook->chapters()->orderBy('chapter_number')->first();
            }
        }

        $chapterAudioUrl = $version->getChapterAudioUrl($book->book_number, $chapter->chapter_number);

        return $this->bibleView('chapter', compact(
            'version', 'book', 'chapter', 'verses', 'previousChapter', 'nextChapter', 'chapterAudioUrl'
        ));
    }

    public function search()
    {
        $versions = BibleVersion::active()->get();
        $defaultVersion = BibleVersion::default()->first()
            ?? BibleVersion::active()->first();
        $defaultVersionAbbr = $defaultVersion?->abbreviation ?? 'NVI';

        return $this->bibleView('search', compact('versions', 'defaultVersionAbbr'));
    }

    public function performSearch(Request $request)
    {
        $query = $request->get('q');

        if (strlen((string) $query) < 3) {
            return response()->json([]);
        }

        $verses = Verse::where('text', 'LIKE', "%{$query}%")
            ->join('chapters', 'verses.chapter_id', '=', 'chapters.id')
            ->join('books', 'chapters.book_id', '=', 'books.id')
            ->select(
                'verses.id',
                'verses.text',
                'verses.verse_number',
                'chapters.chapter_number',
                'books.name as book_name',
                'books.book_number'
            )
            ->limit(50)
            ->get();

        return response()->json($verses);
    }

    public function favorites()
    {
        $user = Auth::user();

        $favorites = Verse::join('bible_favorites', 'verses.id', '=', 'bible_favorites.verse_id')
            ->where('bible_favorites.user_id', $user->id)
            ->with(['chapter.book.bibleVersion'])
            ->select('verses.*', 'bible_favorites.color as favorite_color')
            ->get();

        return $this->bibleView('favorites', compact('favorites'));
    }

    public function addFavorite(Request $request, Verse $verse)
    {
        $user = Auth::user();
        $color = $request->get('color', null);

        $exists = DB::table('bible_favorites')
            ->where('user_id', $user->id)
            ->where('verse_id', $verse->id)
            ->exists();

        if ($exists) {
            DB::table('bible_favorites')
                ->where('user_id', $user->id)
                ->where('verse_id', $verse->id)
                ->update([
                    'color' => $color,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('bible_favorites')->insert([
                'user_id' => $user->id,
                'verse_id' => $verse->id,
                'color' => $color,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function removeFavorite(Verse $verse)
    {
        $user = Auth::user();
        DB::table('bible_favorites')
            ->where('user_id', $user->id)
            ->where('verse_id', $verse->id)
            ->delete();

        return response()->json(['success' => true]);
    }

    public function verse(Verse $verse)
    {
        $verse->load(['chapter.book.bibleVersion']);

        return $this->bibleView('verse', compact('verse'));
    }
}
