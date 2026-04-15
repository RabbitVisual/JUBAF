<?php

namespace Modules\Bible\App\Models;

use Illuminate\Database\Eloquent\Model;

class BibleStrongsLexicon extends Model
{
    protected $table = 'bible_strongs_lexicon';

    protected $fillable = [
        'strong_number',
        'lemma',
        'xlit',
        'pronounce',
        'description',
        'description_original',
        'lemma_br',
        'traduzido_pt',
        'semantic_equivalent_pt',
        'meaning_usage_pt',
        'admin_locked',
        'description_frozen',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'traduzido_pt' => 'boolean',
            'admin_locked' => 'boolean',
            'description_frozen' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public static function normalizeNumber(string $raw): string
    {
        if (preg_match('/([HG]\d+)/i', $raw, $m)) {
            return strtoupper($m[1]);
        }

        return strtoupper(trim($raw));
    }

    public function toApiArray(): array
    {
        return [
            'number' => $this->strong_number,
            'lemma' => $this->lemma,
            'xlit' => $this->xlit,
            'pronounce' => $this->pronounce,
            'description' => $this->description,
            'description_original' => $this->description_original,
            'description_frozen' => (bool) $this->description_frozen,
            'lemma_br' => $this->lemma_br,
            'semantic_equivalent_pt' => $this->semantic_equivalent_pt,
            'meaning_usage_pt' => $this->meaning_usage_pt,
        ];
    }
}
