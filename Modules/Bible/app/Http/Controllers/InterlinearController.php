<?php

namespace Modules\Bible\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Bible\App\Models\BibleStrongsLexicon;
use Modules\Bible\App\Models\BibleVersion;
use Modules\Bible\App\Models\Book;
use Modules\Bible\App\Services\InterlinearStudyService;
use Modules\Bible\App\Support\InterlinearCanon;

class InterlinearController extends Controller
{
    public function __construct(
        private InterlinearStudyService $study
    ) {}

    public function index(Request $request)
    {
        return view('bible::public.interlinear', [
            'bibleVersions' => $this->study->activeVersionsForSelect(),
        ]);
    }

    /**
     * Interlinear dentro do painel do jovem (mesmo corpo, layout do painel).
     */
    public function panelIndex(Request $request)
    {
        $default = BibleVersion::query()->where('is_active', true)->where('is_default', true)->first()
            ?? BibleVersion::query()->where('is_active', true)->orderBy('id')->first();
        $panelInterlinearBackUrl = $default
            ? route('member.bible.read', ['version' => $default->abbreviation])
            : route('dashboard');

        return view('bible::paineljovens.interlinear', [
            'bibleVersions' => $this->study->activeVersionsForSelect(),
            'panelInterlinearBackUrl' => $panelInterlinearBackUrl,
        ]);
    }

    /**
     * Interlinear no painel de líderes (layout e rotas lideres.bible.*).
     */
    public function panelLiderIndex(Request $request)
    {
        $default = BibleVersion::query()->where('is_active', true)->where('is_default', true)->first()
            ?? BibleVersion::query()->where('is_active', true)->orderBy('id')->first();
        $panelInterlinearBackUrl = $default
            ? route('lideres.bible.read', ['version' => $default->abbreviation])
            : route('lideres.dashboard');

        return view('bible::painellider.interlinear', [
            'bibleVersions' => $this->study->activeVersionsForSelect(),
            'panelInterlinearBackUrl' => $panelInterlinearBackUrl,
        ]);
    }

    /**
     * Interlinear no painel de jovens (rotas jovens.bible.*).
     */
    public function panelJovensIndex(Request $request)
    {
        $default = BibleVersion::query()->where('is_active', true)->where('is_default', true)->first()
            ?? BibleVersion::query()->where('is_active', true)->orderBy('id')->first();
        $panelInterlinearBackUrl = $default
            ? route('jovens.bible.read', ['version' => $default->abbreviation])
            : route('jovens.dashboard');

        return view('bible::paineljovens.interlinear', [
            'bibleVersions' => $this->study->activeVersionsForSelect(),
            'panelInterlinearBackUrl' => $panelInterlinearBackUrl,
        ]);
    }

    /**
     * Leitor interlinear na área Bíblia digital (SuperAdmin ou Diretoria — mesma rota, contexto via middleware).
     */
    public function panelBibleAdminIndex(Request $request)
    {
        $prefix = $request->attributes->get('bible_admin_route_prefix', 'admin.bible');
        $root = $request->attributes->get('bible_admin_view_root', 'admin');

        $panelInterlinearBackUrl = $prefix === 'diretoria.bible'
            ? route('diretoria.bible.index')
            : route('admin.bible.index');

        return view('bible::'.$root.'.interlinear', [
            'bibleVersions' => $this->study->activeVersionsForSelect(),
            'panelInterlinearBackUrl' => $panelInterlinearBackUrl,
        ]);
    }

    public function versions()
    {
        return response()->json($this->study->activeVersionsForSelect()->values());
    }

    public function getBooksMetadata()
    {
        try {
            $version = BibleVersion::where('is_active', true)->first();
            if (! $version) {
                Log::warning('Interlinear: No active bible version found.');

                return response()->json([]);
            }

            $books = Book::where('bible_version_id', $version->id)
                ->orderBy('book_number')
                ->select(['name', 'testament', 'total_chapters', 'book_number'])
                ->get();

            return response()->json($books);
        } catch (\Exception $e) {
            Log::error('Interlinear Error in getBooksMetadata: '.$e->getMessage());

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getData(Request $request)
    {
        $bookParam = $request->query('book', 'Genesis');
        $chapter = (int) $request->query('chapter', 1);
        $testament = $request->query('testament', 'old');
        $versionAbbrev = $request->query('version');
        $compareRaw = $request->query('compare', '');
        $compare = array_filter(array_map('trim', explode(',', (string) $compareRaw)));

        $bookEnglish = $this->resolveBookName($bookParam);
        $payload = $this->study->getChapterPayload(
            $bookEnglish,
            $chapter,
            $testament === 'new' ? 'new' : 'old',
            $versionAbbrev ? (string) $versionAbbrev : null,
            $compare !== [] ? $compare : null
        );

        if (isset($payload['error'])) {
            $status = in_array($payload['error'], ['interlinear_not_imported', 'book_not_resolved'], true)
                ? 422
                : 404;

            return response()->json($payload, $status);
        }

        return response()->json($payload);
    }

    public function getStrongDefinition(string $number): \Illuminate\Http\JsonResponse
    {
        $number = BibleStrongsLexicon::normalizeNumber((string) $number);
        $def = $this->study->getStrongDefinition($number);

        if (! $def) {
            return response()->json(['error' => 'Definition not found'], 404);
        }

        return response()->json($def);
    }

    public function getStrongOccurrences(string $number): \Illuminate\Http\JsonResponse
    {
        $data = $this->study->getStrongOccurrences($number);

        return response()->json($data);
    }

    protected function resolveBookName(string $name): string
    {
        $normalized = InterlinearCanon::englishNameFromAny($name);

        if (InterlinearCanon::bookNumberFromEnglishName($normalized) !== null) {
            return $normalized;
        }

        $book = Book::where('name', $name)->first();
        if ($book) {
            $english = InterlinearCanon::englishNameFromBookNumber((int) $book->book_number);
            if ($english) {
                Log::info("Interlinear Resolution (DB): '{$name}' -> '{$english}'");

                return $english;
            }
        }

        return $normalized;
    }
}
