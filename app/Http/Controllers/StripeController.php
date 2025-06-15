<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class StripeController extends Controller
{
    public function pay(Request $request)
    {
        $path = session('filename');
        $lang = session('lang');
        $pageCount = session('page_count');

        if (!$path || !$lang || !$pageCount) {
            return back()->with('error', 'Sessiya maʼlumotlari topilmadi. Iltimos, faylni qaytadan yuklang.');
        }

        $freePages = min(5, $pageCount);
        $paidPages = max(0, $pageCount - $freePages);
        $priceSom = $paidPages * 100;
        $priceUsd = max(0.50, $priceSom / 12500); // minimal $0.50

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'PDF tarjima xizmati',
                    ],
                    'unit_amount' => round($priceUsd * 100), // sent
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success'),
            'cancel_url' => route('stripe.cancel'),
        ]);

        return response()->json(['id' => $session->id]);
    }


    public function success()
    {
        // Tarjimani faollashtirish
        $path = Session::get('filename');
        $lang = Session::get('lang');
        $pageCount = Session::get('pageCount');

        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile(storage_path('app/' . $path));
        $pages = $pdf->getPages();

        $text = '';
        foreach ($pages as $i => $page) {
            if ($i < 5 || $pageCount <= 5) {
                $text .= $page->getText() . "\n";
            }
        }

        // Tarjima qilish
        $translated = app(TranslationController::class)->translateText($text, $lang);

        $pdfOut = Pdf::loadHTML('<h2>Tarjima</h2><p>' . nl2br(e($translated)) . '</p>');
        $outputFile = 'translations/' . time() . '.pdf';
        Storage::put('public/' . $outputFile, $pdfOut->output());

        return response()->download(storage_path('app/public/' . $outputFile));
    }

    public function cancel()
    {
        return "❌ To‘lov bekor qilindi.";
    }
}
