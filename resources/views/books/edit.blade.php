@extends('layouts.app')

@section('title', 'Kitobni tahrirlash')

@section('content')
    <h1>Kitobni tahrirlash</h1>
    <form method="POST" action="{{ route('books.update', $book->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nomi</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ $book->title }}" required>
            @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Muallif</label>
            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ $book->author }}" required>
            @error('author')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Janr</label>
            <input type="text" name="genre" class="form-control @error('genre') is-invalid @enderror" value="{{ $book->genre }}" required>
            @error('genre')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Til</label>
            <input type="text" name="language" class="form-control @error('language') is-invalid @enderror" value="{{ $book->language }}" required>
            @error('language')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Nashr yili</label>
            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ $book->year }}" required>
            @error('year')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Yangi kitob fayli (PDF, DOC, EPUB)</label>
            <input type="file" name="book_file" class="form-control-file @error('book_file') is-invalid @enderror">
            @error('book_file')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Yangi muqova rasmi (JPEG, PNG, JPG)</label>
            <input type="file" name="cover_image" class="form-control-file @error('cover_image') is-invalid @enderror">
            @error('cover_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if ($book->cover_image)
                <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" style="width: 100px; height: 150px; object-fit: cover; margin-top: 10px;">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Yangilash</button>
    </form>
@endsection
