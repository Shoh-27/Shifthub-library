<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->is_admin; // Faqat adminlar uchun
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string',
            'language' => 'required|string',
            'year' => 'required|integer|min:1000|max:' . date('Y'),
            'book_file' => 'nullable|file|mimes:pdf,doc,docx,epub|max:10000',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }
}
