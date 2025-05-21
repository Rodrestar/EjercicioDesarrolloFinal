<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/analyze.php';

class AnalyzeTest extends TestCase {
    public function testAnalyzeTextBasic() {
        $input = json_encode(['text' => 'Hola mundo mundo']);
        $outputJson = analyzeText($input);
        $output = json_decode($outputJson, true);

        $this->assertArrayHasKey('mundo', $output);
        $this->assertEquals(2, $output['mundo']);
    }
}
