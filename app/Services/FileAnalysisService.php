<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;

class FileAnalysisService
{
    public function getPageCount(string $fullPath): int
    {
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        try {
            if ($extension === 'pdf') {
                $parser = new Parser();
                $pdf = $parser->parseFile($fullPath);
                return count($pdf->getPages());
            }

            if (in_array($extension, ['doc', 'docx'])) {
                $phpWord = WordIOFactory::load($fullPath);
                return count($phpWord->getSections());
            }
        } catch (\Exception $e) {
            Log::error("Page count detection failed for $fullPath: " . $e->getMessage());
        }

        return 0;
    }
}
