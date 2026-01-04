<?php

namespace App\Helpers;

use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory as WordIO;

class FileTextExtractor
{
    /**
     * Extract text from a single file
     */
    public static function extract(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $text = '';

        if ($ext === 'pdf') {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($path);
            $text = $pdf->getText();
        } elseif ($ext === 'docx') {
            $phpWord = WordIO::load($path);
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    }
                }
            }
        } elseif ($ext === 'txt') {
            $text = file_get_contents($path);
        }

        return $text;
    }

    /**
     * Extract text from multiple files (array)
     */
    public static function extractMultiple(array $paths): string
    {
        $allText = '';
        foreach ($paths as $path) {
            $allText .= self::extract($path) . "\n";
        }
        return $allText;
    }
}
