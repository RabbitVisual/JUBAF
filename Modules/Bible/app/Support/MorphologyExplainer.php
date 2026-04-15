<?php

namespace Modules\Bible\App\Support;

/**
 * Explicações curtas em PT para códigos de morfologia frequentes (Hebraico / Grego abreviado).
 */
final class MorphologyExplainer
{
    private const HEBREW_LEX = [
        'V' => 'Verbo',
        'N' => 'Nome',
        'PRT' => 'Partícula',
        'PREP' => 'Preposição',
        'CONJ' => 'Conjunção',
        'ADV' => 'Advérbio',
        'ADJ' => 'Adjetivo',
        'PRON' => 'Pronome',
        'SUFF' => 'Sufixo pronominal',
        'INJ' => 'Interjeição',
        'QAL' => 'Qal (verbo)',
        'PIEL' => 'Piel',
        'HIPH' => 'Hifil',
        'HOPH' => 'Hofal',
        'NIF' => 'Nifal',
        'PUAL' => 'Pual',
        'HIT' => 'Hitpael',
        'WAY' => 'Wayyiqtol (narração)',
        'IMP' => 'Imperativo',
        'INF' => 'Infinitivo',
        'PTCA' => 'Participio activo',
        'PTCP' => 'Participio',
        'MS' => 'Masculino singular',
        'MP' => 'Masculino plural',
        'FS' => 'Feminino singular',
        'FP' => 'Feminino plural',
        '3MS' => '3.ª pessoa m. singular',
        '3FS' => '3.ª pessoa f. singular',
        '2MS' => '2.ª pessoa m. singular',
        '1CS' => '1.ª comum singular',
    ];

    private const GREEK_LEX = [
        'V-' => 'Verbo',
        'N-' => 'Nome',
        'A-' => 'Adjetivo',
        'P-' => 'Pronome',
        'R-' => 'Pronome relativo',
        'C-' => 'Conjunção',
        'D-' => 'Advérbio',
        'PREP' => 'Preposição',
        'ART' => 'Artigo',
        'T-' => 'Tempos verbal',
        'M/P' => 'Voz média/passiva',
        'PAS' => 'Passiva',
        'ACT' => 'Ativa',
        'MID' => 'Média',
        'SG' => 'Singular',
        'PL' => 'Plural',
        'NSM' => 'Nominativo m. singular',
        'GSM' => 'Genitivo m. singular',
        'DSM' => 'Dativo m. singular',
        'ASM' => 'Accusativo m. singular',
    ];

    public static function humanize(?string $tag, string $testament = 'old'): string
    {
        if ($tag === null || trim($tag) === '') {
            return '';
        }

        $tag = trim($tag);
        $parts = preg_split('/[\s\-\/]+/', $tag, -1, PREG_SPLIT_NO_EMPTY);
        if ($parts === false || $parts === []) {
            return '';
        }

        $lex = $testament === 'new' ? self::GREEK_LEX : self::HEBREW_LEX;
        $seen = [];
        $out = [];

        foreach ($parts as $p) {
            $key = strtoupper($p);
            if (isset($lex[$key]) && ! isset($seen[$key])) {
                $seen[$key] = true;
                $out[] = $lex[$key];
            } elseif (isset($lex[$p]) && ! isset($seen[$p])) {
                $seen[$p] = true;
                $out[] = $lex[$p];
            }
        }

        if ($out === []) {
            return 'Código morfológico (consulte o léxico para detalhes).';
        }

        return implode(' · ', array_slice($out, 0, 6));
    }
}
