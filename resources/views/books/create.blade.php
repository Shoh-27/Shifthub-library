@extends('layouts.app')

@section('title', 'Yangi kitob qo‘shish')

@section('content')
    <h1>Yangi kitob qo‘shish</h1>
    <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Nomi</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
            @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Muallif</label>
            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}" required>
            @error('author')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Janr</label>
            <input type="text" name="genre" class="form-control @error('genre') is-invalid @enderror" value="{{ old('genre') }}" required>
            @error('genre')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Til</label>
            <input type="text" name="language" class="form-control @error('language') is-invalid @enderror" value="{{ old('language') }}" required>
            @error('language')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Nashr yili</label>
            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year') }}" required>
            @error('year')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Kitob fayli (PDF, DOC, EPUB)</label>
            <input type="file" name="book_file" class="form-control-file @error('book_file') is-invalid @enderror" required>
            @error('book_file')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Muqova rasmi (JPEG, PNG, JPG)</label>
            <input type="file" name="cover_image" class="form-control-file @error('cover_image') is-invalid @enderror">
            @error('cover_image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Yuklash</button>
    </form>
@endsection
