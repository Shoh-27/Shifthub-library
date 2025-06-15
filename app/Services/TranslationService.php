<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Spatie\PdfToText\Pdf;

use Dompdf\Dompdf;

class TranslationService
{
    public function translatePdf(string $filePath, string $targetLang = 'uz'): ?string
    {
        try {
            // 1. Matnni ajratish
            $pdfText = Pdf::getText($filePath);

            if (empty(trim($pdfText))) {
                Log::error("PDF fayldan matn ajratib bo‘lmadi.");
                return null;
            }

            // 2. Matnni tarjima qilish
            $translatedText = $this->translateText($pdfText, $targetLang);

            if (!$translatedText) {
                Log::error("Tarjima qilishda xatolik yuz berdi.");
                return null;
            }

            // 3. Yangi PDF hosil qilish
            $translatedPdfPath = 'translated/translated_' . uniqid() . '.pdf';
            $this->createPdf($translatedText, storage_path("app/" . $translatedPdfPath));

            return $translatedPdfPath;

        } catch (\Exception $e) {
            Log::error("PDF tarjimada xatolik: " . $e->getMessage());
            return null;
        }
    }

    private function translateText(string $text, string $target): ?string
    {
        $apiKey = env('GOOGLE_TRANSLATE_API_KEY');

        $response = Http::post("https://translation.googleapis.com/language/translate/v2?key={$apiKey}", [
            'q' => $text,
            'target' => $target,
            'format' => 'text',
        ]);

        if ($response->successful()) {
            return $response->json()['data']['translations'][0]['translatedText'];
        }

        Log::error("Google Translate javobi: " . $response->body());
        return null;
    }

    private function createPdf(string $text, string $outputPath)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml(nl2br(e($text)));
        $dompdf->render();
        file_put_contents($outputPath, $dompdf->output());
    }
}
