<?php

namespace Modules\Bible\App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource for GET /find response (reference string lookup).
 * Exposes verses and full_chapter_url for clients (e.g. reader UIs).
 */
class FindByReferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'reference' => $this->resource['reference'],
            'book' => $this->resource['book'],
            'book_number' => $this->resource['book_number'] ?? null,
            'chapter' => $this->resource['chapter'],
            'verse_start' => $this->resource['verse_start'] ?? null,
            'verse_end' => $this->resource['verse_end'] ?? null,
            'verses' => VerseResource::collection($this->resource['verses']),
            'full_chapter_url' => $this->resource['full_chapter_url'],
            'bible_version_id' => $this->resource['bible_version_id'] ?? null,
            'version_abbreviation' => $this->resource['version_abbreviation'] ?? null,
        ];
    }
}
