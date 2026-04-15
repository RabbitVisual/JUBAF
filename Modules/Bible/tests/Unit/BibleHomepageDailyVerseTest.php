<?php

namespace Modules\Bible\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Modules\Bible\App\Models\BibleVersion;
use Modules\Bible\App\Models\Book;
use Modules\Bible\App\Models\Chapter;
use Modules\Bible\App\Models\Verse;
use Modules\Bible\App\Services\BibleApiService;
use Tests\TestCase;

class BibleHomepageDailyVerseTest extends TestCase
{
    use RefreshDatabase;

    private function seedMiniBible(): BibleVersion
    {
        $version = BibleVersion::create([
            'name' => 'Test Version',
            'abbreviation' => 'TV',
            'is_active' => true,
            'is_default' => true,
        ]);

        $book = Book::create([
            'bible_version_id' => $version->id,
            'name' => 'João',
            'book_number' => 43,
            'abbreviation' => 'Jo',
            'testament' => 'new',
        ]);

        $chapter = Chapter::create([
            'book_id' => $book->id,
            'chapter_number' => 3,
        ]);

        for ($i = 1; $i <= 5; $i++) {
            Verse::create([
                'chapter_id' => $chapter->id,
                'verse_number' => $i,
                'text' => "Texto do versículo {$i}",
            ]);
        }

        return $version;
    }

    public function test_same_date_same_verse_deterministic(): void
    {
        $version = $this->seedMiniBible();
        $service = app(BibleApiService::class);
        $fixed = Carbon::parse('2026-06-15 12:00:00', config('app.timezone'));

        $a = $service->getHomepageDailyVersePayload($version->id, '', 'testsalt', $fixed);
        $b = $service->getHomepageDailyVersePayload($version->id, '', 'testsalt', $fixed);

        $this->assertNotNull($a);
        $this->assertSame($a['text'], $b['text']);
        $this->assertSame($a['reference'], $b['reference']);
        $this->assertSame('daily', $a['mode']);
    }

    public function test_different_date_can_change_verse(): void
    {
        $version = $this->seedMiniBible();
        $service = app(BibleApiService::class);

        $d1 = $service->getHomepageDailyVersePayload($version->id, '', 'testsalt', Carbon::parse('2026-01-01'));
        $d2 = $service->getHomepageDailyVersePayload($version->id, '', 'testsalt', Carbon::parse('2026-12-31'));

        $this->assertNotNull($d1);
        $this->assertNotNull($d2);
        $this->assertNotSame($d1['text'], $d2['text']);
    }

    public function test_override_reference_returns_fixed_passage(): void
    {
        $version = $this->seedMiniBible();
        $service = app(BibleApiService::class);
        Cache::flush();

        $payload = $service->getHomepageDailyVersePayload($version->id, 'João 3:2', 'x', Carbon::now());

        $this->assertNotNull($payload);
        $this->assertStringContainsString('Texto do versículo 2', $payload['text']);
        $this->assertSame('override', $payload['mode']);
    }
}
