<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\TranslationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;

Route::group(['middleware' => ['web' ]], function () {
    Route::get('/', fn() => redirect()->route('books.index'));


    Route::post('/stripe/pay', [StripeController::class, 'pay'])->name('stripe.pay');
    Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');


    // Kitoblar routelari
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::get('/books/{book}/download', [BookController::class, 'download'])->name('books.download');
    Route::post('/books/{book}/translate', [BookController::class, 'translate'])->name('books.translate');

    // Foydalanuvchi fayl tarjimasi
//    Route::get('/translate', [TranslationController::class, 'create'])->name('translations.create');
//    Route::post('/translate', [TranslationController::class, 'store'])->middleware('throttle:10,3')->name('translations.store');
//    Route::get('/translate/success/{payment_id}', [TranslationController::class, 'success'])->name('translate.success');
//    Route::get('/translate/cancel', [TranslationController::class, 'cancel'])->name('translate.cancel');

//    Route::get('/translate', [TranslationController::class, 'showForm'])->name('translate.form');
//    Route::post('/translate', [TranslationController::class, 'translate'])->name('translate.submit');
    Route::post('/translate', [TranslationController::class, 'translate'])->name('translations.translate');
    Route::post('/translate/preview', [TranslationController::class, 'preview'])->name('translations.preview');
    Route::get('/translate', [TranslationController::class, 'index'])->name('translations.create');


    // Autentifikatsiya routelari
    Auth::routes();

    // Foydalanuvchi routelari
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/user/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');

    // Admin routelari
    Route::prefix('admin')->middleware(['admin', 'web'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/books', [AdminController::class, 'books'])->name('admin.books');
        Route::get('/books/{book}/edit', [AdminController::class, 'editBook'])->name('admin.books.edit');
        Route::put('/books/{book}', [AdminController::class, 'updateBook'])->name('admin.books.update');
        Route::delete('/books/{book}', [AdminController::class, 'destroyBook'])->name('admin.books.destroy');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    });

    // Til o‘zgartirish routesi
    Route::get('/language/switch/{locale}', [LanguageController::class, 'switch'])->name('language.switch');
});
