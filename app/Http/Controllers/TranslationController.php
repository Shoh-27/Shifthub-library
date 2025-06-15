<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\TranslationService;
use GuzzleHttp\Client;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;

class TranslationController extends Controller
{
    public function index()
    {
        return view('translations.create');
    }

    public function translate(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx',
            'lang' => 'required|string'
        ]);

        $file = $request->file('file');
        $lang = $request->input('lang');

        // Faylni vaqtincha saqlash
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads', $filename);

        // Matnni ajratish
        $text = $this->extractText(storage_path('app/' . $path));

        if (!$text) {
            return back()->with('error', 'Fayldan matn ajratib bo‘lmadi.');
        }

        // Tarjima qilish
        $translatedText = $this->translateText($text, $lang);

        if (!$translatedText) {
            return back()->with('error', 'Tarjima amalga oshmadi.');
        }

        // PDF yaratish
        $pdf = Pdf::loadHTML('<h1>Tarjima matni</h1><p>' . nl2br(e($translatedText)) . '</p>');
        $outputPath = 'translations/' . time() . '.pdf';
        Storage::put('public/' . $outputPath, $pdf->output());

        return response()->download(storage_path('app/public/' . $outputPath));
    }

    private function extractText($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        if ($extension === 'pdf') {
            try {
                $parser = new PdfParser();
                $pdf = $parser->parseFile($filePath);
                return $pdf->getText();
            } catch (\Exception $e) {
                \Log::error("PDF parsing error: " . $e->getMessage());
                return null;
            }
        }

        if (in_array($extension, ['doc', 'docx'])) {
            try {
                $phpWord = WordIOFactory::load($filePath);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
                return $text;
            } catch (\Exception $e) {
                \Log::error("Word file reading error: " . $e->getMessage());
                return null;
            }
        }

        return null;
    }

    public function translateText($text, $lang)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://translation.googleapis.com/language/translate/v2', [
                'query' => [
                    'key' => env('GOOGLE_TRANSLATE_API_KEY'),
                ],
                'form_params' => [
                    'q' => $text,
                    'target' => $lang,
                    'format' => 'text'
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            return $result['data']['translations'][0]['translatedText'] ?? null;
        } catch (\Exception $e) {
            \Log::error('Translate error: ' . $e->getMessage());
            return null;
        }
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx',
            'lang' => 'required|string',
        ]);

        $file = $request->file('file');
        $lang = $request->input('lang');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads', $filename);

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile(storage_path('app/' . $path));
        $pages = $pdf->getPages();
        $freePages = array_slice($pages, 0, 5);

        $text = '';
        foreach ($freePages as $page) {
            $text .= $page->getText() . "\n";
        }

        $translated = $this->translateText($text, $lang);

        // sessiyada keyinchalik ishlatish uchun saqlaymiz
        session([
            'filename' => $path,
            'lang' => $lang,
            'page_count' => count($pages),
        ]);

        return view('translations.preview', [
            'translatedText' => $translated,
            'lang' => $lang,
            'filename' => $path,
        ]);
    }

}
