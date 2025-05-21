<?php

require_once __DIR__ . '/TextAnalyzer.php';

// Función para validar que el texto solo contenga letras, espacios y vocales con o sin tilde
function validateText(string $text): bool {
    // Validación: solo letras (a-z, A-Z), tildes, ñ, ü y espacios
    return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/', $text);
}

function analyzeText(string $jsonInput): string {
    $data = json_decode($jsonInput, true);
    $text = $data['text'] ?? '';

    // Verificar si se recibió el texto correctamente
    if (empty($text)) {
        return json_encode(['error' => 'No se recibió texto'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    // Limpiar saltos de línea y espacios extra
    $text = trim(preg_replace('/\s+/', ' ', $text));  // Reemplaza múltiples espacios por uno solo y elimina los saltos de línea

    // Limpiar la puntuación y validar el texto
    $text = TextAnalyzer::removePunctuation($text);  // Eliminar puntuación
    if (!validateText($text)) {
        return json_encode(['error' => 'El texto solo debe contener letras, espacios y vocales con o sin tilde.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    // Normalizar el texto (convertir a minúsculas, eliminar tildes)
    $text = TextAnalyzer::normalize($text);  
    $text = TextAnalyzer::removeAccents($text);  

    // Separar palabras
    $words = TextAnalyzer::splitWords($text);

    // Cargar stopwords y eliminar las no significativas
    $stopwords = TextAnalyzer::loadStopwords(__DIR__ . '/../stopwords/es.txt');
    $filtered = TextAnalyzer::removeStopwords($words, $stopwords);

    // Contar las frecuencias de las palabras
    $frequencies = TextAnalyzer::countFrequencies($filtered);

    return json_encode($frequencies, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

header('Content-Type: application/json');
$input = file_get_contents('php://input');
echo analyzeText($input);
