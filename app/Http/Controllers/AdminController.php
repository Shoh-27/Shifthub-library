<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Http\Requests\BookUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $totalBooks = Book::count();
        $totalUsers = User::count();
        $recentBooks = Book::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalBooks', 'totalUsers', 'recentBooks', 'recentUsers'));
    }

    public function books()
    {
        $books = Book::with('user')->paginate(10);
        return view('admin.books', compact('books'));
    }

    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    public function editBook(Book $book)
    {
        return view('admin.books_edit', compact('book'));
    }

    public function updateBook(BookUpdateRequest $request, Book $book)
    {
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
        return redirect()->route('admin.books')->with('success', 'Kitob muvaffaqiyatli yangilandi');
    }

    public function destroyBook(Book $book)
    {
        Storage::disk('public')->delete($book->file_path);
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }
        $book->delete();
        return redirect()->route('admin.books')->with('success', 'Kitob o‘chirildi');
    }

    public function destroyUser(User $user)
    {
        if ($user->is_admin) {
            return redirect()->route('admin.users')->with('error', 'Adminni o‘chirib bo‘lmaydi');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Foydalanuvchi o‘chirildi');
    }
}
