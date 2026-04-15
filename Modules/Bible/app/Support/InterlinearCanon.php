<?php

namespace Modules\Bible\App\Support;

/**
 * Canonical English book names and Protestant book_number (1–66) for interlinear alignment.
 */
final class InterlinearCanon
{
    /** @var array<string, int> 0-based index in standard JSON arrays (Genesis = 0) */
    private const BOOK_INDEX = [
        'Genesis' => 0, 'Exodus' => 1, 'Leviticus' => 2, 'Numbers' => 3, 'Deuteronomy' => 4,
        'Joshua' => 5, 'Judges' => 6, 'Ruth' => 7,
        'I Samuel' => 8, '1 Samuel' => 8, 'II Samuel' => 9, '2 Samuel' => 9,
        'I Kings' => 10, '1 Kings' => 10, 'II Kings' => 11, '2 Kings' => 11,
        'I Chronicles' => 12, '1 Chronicles' => 12, 'II Chronicles' => 13, '2 Chronicles' => 13,
        'Ezra' => 14, 'Nehemiah' => 15, 'Esther' => 16, 'Job' => 17, 'Psalms' => 18,
        'Proverbs' => 19, 'Ecclesiastes' => 20, 'Song of Solomon' => 21, 'Isaiah' => 22,
        'Jeremiah' => 23, 'Lamentations' => 24, 'Ezekiel' => 25, 'Daniel' => 26,
        'Hosea' => 27, 'Joel' => 28, 'Amos' => 29, 'Obadiah' => 30, 'Jonah' => 31,
        'Micah' => 32, 'Nahum' => 33, 'Habakkuk' => 34, 'Zephaniah' => 35, 'Haggai' => 36,
        'Zechariah' => 37, 'Malachi' => 38,
        'Matthew' => 39, 'Mark' => 40, 'Luke' => 41, 'John' => 42, 'Acts' => 43,
        'Romans' => 44, '1 Corinthians' => 45, '2 Corinthians' => 46, 'Galatians' => 47,
        'Ephesians' => 48, 'Philippians' => 49, 'Colossians' => 50, '1 Thessalonians' => 51,
        '2 Thessalonians' => 52, '1 Timothy' => 53, '2 Timothy' => 54, 'Titus' => 55,
        'Philemon' => 56, 'Hebreus' => 57, 'Hebrews' => 57, 'Tiago' => 58, 'James' => 58,
        '1 Pedro' => 59, '1 Peter' => 59, '2 Pedro' => 60, '2 Peter' => 60,
        '1 João' => 61, '1 John' => 61, '2 João' => 62, '2 John' => 62, '3 João' => 63, '3 John' => 63,
        'Judas' => 64, 'Jude' => 64, 'Apocalipse' => 65, 'Revelation' => 65,
    ];

    /** @var array<string, string> Portuguese / alias → English canonical key */
    private const PT_TO_ENGLISH = [
        'Gênesis' => 'Genesis', 'Genesis' => 'Genesis',
        'Êxodo' => 'Exodus', 'Exodo' => 'Exodus',
        'Levítico' => 'Leviticus', 'Levitico' => 'Leviticus',
        'Números' => 'Numbers', 'Numeros' => 'Numbers',
        'Deuteronômio' => 'Deuteronomy', 'Deuteronomio' => 'Deuteronomy',
        'Josué' => 'Joshua', 'Josue' => 'Joshua',
        'Juízes' => 'Judges', 'Juizes' => 'Judges',
        'Rute' => 'Ruth',
        '1 Samuel' => 'I Samuel', 'I Samuel' => 'I Samuel',
        '2 Samuel' => 'II Samuel', 'II Samuel' => 'II Samuel',
        '1 Reis' => 'I Kings', '2 Reis' => 'II Kings',
        '1 Crônicas' => 'I Chronicles', '2 Crônicas' => 'II Chronicles',
        'Esdras' => 'Ezra', 'Neemias' => 'Nehemiah', 'Ester' => 'Esther', 'Jó' => 'Job', 'Jo' => 'Job',
        'Salmos' => 'Psalms', 'Provérbios' => 'Proverbs', 'Proverbios' => 'Proverbs',
        'Eclesiastes' => 'Ecclesiastes', 'Cânticos' => 'Song of Solomon', 'Canticos' => 'Song of Solomon',
        'Isaías' => 'Isaiah', 'Isaias' => 'Isaiah',
        'Jeremias' => 'Jeremiah', 'Lamentações' => 'Lamentations', 'Lamentacoes' => 'Lamentations',
        'Ezequiel' => 'Ezekiel', 'Daniel' => 'Daniel',
        'Oseias' => 'Hosea', 'Joel' => 'Joel', 'Amós' => 'Amos', 'Amos' => 'Amos',
        'Obadias' => 'Obadiah', 'Jonas' => 'Jonah', 'Miqueias' => 'Micah',
        'Naum' => 'Nahum', 'Habacuque' => 'Habakkuk', 'Sofonias' => 'Zephaniah',
        'Ageu' => 'Haggai', 'Zacarias' => 'Zechariah', 'Malaquias' => 'Malachi',
        'Mateus' => 'Matthew', 'Marcos' => 'Mark', 'Lucas' => 'Luke', 'João' => 'John', 'Joao' => 'John',
        'Atos' => 'Acts', 'Romanos' => 'Romans',
        '1 Coríntios' => '1 Corinthians', '2 Coríntios' => '2 Corinthians',
        'Gálatas' => 'Galatians', 'Efésios' => 'Ephesians',
        'Filipenses' => 'Philippians', 'Colossenses' => 'Colossians',
        '1 Tessalonicenses' => '1 Thessalonians', '2 Tessalonicenses' => '2 Thessalonians',
        '1 Timóteo' => '1 Timothy', '2 Timóteo' => '2 Timothy',
        'Tito' => 'Titus', 'Filemom' => 'Philemon', 'Hebreus' => 'Hebrews',
        'Tiago' => 'James', '1 Pedro' => '1 Peter', '2 Pedro' => '2 Peter',
        '1 João' => '1 John', '2 João' => '2 John', '3 João' => '3 John',
        'Judas' => 'Jude', 'Apocalipse' => 'Revelation',
    ];

    public static function englishNameFromAny(string $name): string
    {
        $name = trim(preg_replace('/\s+/', ' ', $name));

        return self::PT_TO_ENGLISH[$name] ?? $name;
    }

    public static function bookNumberFromEnglishName(string $englishName): ?int
    {
        $englishName = trim(preg_replace('/\s+/', ' ', $englishName));
        if (! isset(self::BOOK_INDEX[$englishName])) {
            return null;
        }

        return self::BOOK_INDEX[$englishName] + 1;
    }

    public static function bookNumberFromDisplayName(string $name): ?int
    {
        $en = self::englishNameFromAny($name);

        return self::bookNumberFromEnglishName($en);
    }

    /** @var array<int, string>|null */
    private static ?array $bookNumberToEnglish = null;

    public static function englishNameFromBookNumber(int $bookNumber): ?string
    {
        if (self::$bookNumberToEnglish === null) {
            self::$bookNumberToEnglish = [];
            foreach (self::BOOK_INDEX as $name => $idx) {
                $n = $idx + 1;
                if (! isset(self::$bookNumberToEnglish[$n])) {
                    self::$bookNumberToEnglish[$n] = $name;
                }
            }
        }

        return self::$bookNumberToEnglish[$bookNumber] ?? null;
    }

    public static function extractStrongsKey(string $raw): string
    {
        if (preg_match('/([HG]\d+)/i', $raw, $m)) {
            return strtoupper($m[1]);
        }

        return strtoupper(trim($raw));
    }
}
