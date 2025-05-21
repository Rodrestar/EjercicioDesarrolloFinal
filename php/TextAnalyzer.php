<?php

class TextAnalyzer {

    public static function normalize(string $text): string {
        return mb_strtolower($text, 'UTF-8');
    }

    public static function removeAccents(string $text): string {
        $unwanted_array = [
            'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u',
            'Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U',
            'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u',
            'À'=>'A','È'=>'E','Ì'=>'I','Ò'=>'O','Ù'=>'U',
            'ä'=>'a','ë'=>'e','ï'=>'i','ö'=>'o','ü'=>'u',
            'Ä'=>'A','Ë'=>'E','Ï'=>'I','Ö'=>'O','Ü'=>'U',
            'ñ'=>'n','Ñ'=>'N',
            'ç'=>'c','Ç'=>'C',
        ];
        return strtr($text, $unwanted_array);
    }

   public static function removePunctuation(string $text): string {
    // Reemplazar todos los caracteres no alfanuméricos o espacios con un espacio
    $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
    
    // Eliminar espacios extra entre palabras
    return preg_replace('/\s+/', ' ', trim($text));
}
    public static function removeExtraSpaces(string $text): string {
        return preg_replace('/\s+/', ' ', trim($text));
    }
    public static function removeLineBreaks(string $text): string {
        return preg_replace('/\r\n|\r|\n/', ' ', $text);
    }
    public static function removeSpecialCharacters(string $text): string {
        return preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
    }
    public static function removeNumbers(string $text): string {
        return preg_replace('/\d+/', '', $text);
    }
    public static function removeHTMLTags(string $text): string {
        return strip_tags($text);
    }


    public static function splitWords(string $text): array {
        return preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    public static function loadStopwords(string $path): array {
        $stopwords = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return array_map('mb_strtolower', array_map('trim', $stopwords));
    }

    public static function removeStopwords(array $words, array $stopwords): array {
        return array_filter($words, fn($word) => !in_array($word, $stopwords));
    }

    public static function countFrequencies(array $words): array {
        $frequencies = array_count_values($words);
        arsort($frequencies);
        return $frequencies;
    }
}
