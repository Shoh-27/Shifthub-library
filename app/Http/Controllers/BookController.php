<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\TranslationService;
use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('admin')->only(['create', 'store']);
        $this->translationService = $translationService;
    }

    public function index()
    {
        $books = Book::filter(request(['title', 'language']))
            ->paginate(10);
        $noResults = $books->isEmpty() && (request('title') || request('language'));

        return view('books.index', compact('books', 'noResults'));
    }

    public function show(Book $book)
    {
        if (auth()->check()) {
            try {
                auth()->user()->readBooks()->syncWithoutDetaching($book->id);
            } catch (\Exception $e) {
                \Log::error('Error in readBooks: ' . $e->getMessage());
            }
        }
        return view('books.show', compact('book'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(BookStoreRequest $request)
    {
        $file = $request->file('book_file');
        $filePath = $file->store('books', 'public');

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'genre' => $request->genre,
            'language' => $request->language,
            'year' => $request->year,
            'file_path' => $filePath,
            'cover_image' => $coverImagePath,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('books.index')->with('success', __('Book added successfully'));
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        return view('books.edit', compact('book'));
    }

    public function update(BookUpdateRequest $request, Book $book)
    {
        $this->authorize('update', $book);

        $data = $request->only(['title', 'author', 'genre', 'language', 'year']);

        if ($request->hasFile('book_file')) {
            Storage::disk('public')->delete($book->file_path);
            $data['file_path'] = $request->file('book_file')->store('books', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);
        return redirect()->route('books.index')->with('success', __('Book updated successfully'));
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        Storage::disk('public')->delete($book->file_path);
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }
        $book->delete();
        return redirect()->route('books.index')->with('success', __('Book deleted successfully'));
    }

    public function download(Book $book)
    {
        return Storage::disk('public')->download($book->file_path);
    }

    public function translate(Request $request, Book $book)
    {
        $request->validate([
            'target_lang' => 'required|in:en,uz',
        ]);

        try {
            $translatedFilePath = $this->translationService->translateFile($book->file_path, $request->target_lang);
            return Storage::disk('public')->download($translatedFilePath);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Translation failed: ') . $e->getMessage());
        }
    }
}
