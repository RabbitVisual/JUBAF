<?php

namespace App\Services;

use Modules\Bible\App\Services\BibleApiService;

class DevotionalScriptureService
{
    private const MAX_VERSES = 40;

    public function __construct(
        private BibleApiService $bibleApi
    ) {}

    /**
     * @return array{plain_text: string, reference: string, bible_version_id: int}|null
     */
    public function resolve(string $reference, ?int $bibleVersionId = null): ?array
    {
        if (! module_enabled('Bible')) {
            return null;
        }

        $found = $this->bibleApi->findByReference($reference, $bibleVersionId);
        if ($found === null) {
            return null;
        }

        if ($found['verses']->count() > self::MAX_VERSES) {
            return null;
        }

        $plain = $found['verses']
            ->map(fn ($v) => $v->verse_number.'. '.$v->text)
            ->implode("\n");

        return [
            'plain_text' => $plain,
            'reference' => $found['reference'],
            'bible_version_id' => (int) $found['bible_version_id'],
        ];
    }
}
