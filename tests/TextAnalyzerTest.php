<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../php/TextAnalyzer.php';

class TextAnalyzerTest extends TestCase {
    public function testNormalize() {
        $this->assertEquals('hola mundo', TextAnalyzer::normalize('HOLA MUNDO'));
    }

    public function testRemovePunctuation() {
        $this->assertEquals('hola mundo esto es', TextAnalyzer::removePunctuation('hola, mundo! esto... es?'));
    }

    public function testSplitWords() {
        $text = "hola mundo prueba";
        $this->assertEquals(['hola', 'mundo', 'prueba'], TextAnalyzer::splitWords($text));
    }

    public function testRemoveStopwords() {
        $words = ['hola', 'mundo', 'de', 'la'];
        $stopwords = ['de', 'la'];
        $this->assertEquals(['hola', 'mundo'], array_values(TextAnalyzer::removeStopwords($words, $stopwords)));
    }

    public function testCountFrequencies() {
        $words = ['hola', 'mundo', 'hola'];
        $expected = ['hola' => 2, 'mundo' => 1];
        $this->assertEquals($expected, TextAnalyzer::countFrequencies($words));
    }
}
