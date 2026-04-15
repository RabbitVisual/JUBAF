<?php

namespace Modules\Bible\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Bible\App\Models\Book;
use Modules\Bible\App\Models\Chapter;
use Modules\Bible\App\Models\Verse;

/**
 * JSON auxiliar para o editor de planos de leitura (SuperAdmin).
 */
class BibleAdminLookupController extends Controller
{
    public function books(Request $request): JsonResponse
    {
        $versionId = (int) $request->query('version_id', 0);
        if ($versionId < 1) {
            return response()->json([]);
        }

        $books = Book::query()
            ->where('bible_version_id', $versionId)
            ->orderBy('book_number')
            ->get(['id', 'name']);

        return response()->json($books);
    }

    public function chapters(Request $request): JsonResponse
    {
        $bookId = (int) $request->query('book_id', 0);
        if ($bookId < 1) {
            return response()->json([]);
        }

        $chapters = Chapter::query()
            ->where('book_id', $bookId)
            ->orderBy('chapter_number')
            ->get(['id', 'chapter_number']);

        return response()->json($chapters);
    }

    public function verses(Request $request): JsonResponse
    {
        $chapterId = (int) $request->query('chapter_id', 0);
        if ($chapterId < 1) {
            return response()->json([]);
        }

        $verses = Verse::query()
            ->where('chapter_id', $chapterId)
            ->orderBy('verse_number')
            ->get(['verse_number', 'text']);

        return response()->json($verses);
    }
}
