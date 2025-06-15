@extends('layouts.app')

@section('title', 'Kitoblar')

@section('content')
    <h1>Kitoblar ro'yxati</h1>
    <form method="GET" action="{{ route('books.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="title" class="form-control" placeholder="Kitob nomi" value="{{ request('title') }}">
            </div>
            <div class="col-md-6">
                <input type="text" name="language" class="form-control" placeholder="Til" value="{{ request('language') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Qidirish</button>
    </form>

    @if ($noResults)
        <div class="alert alert-warning">
            Hech narsa topilmadi
        </div>
    @else
        @foreach ($books as $book)
            <div class="card mb-3">
                <div class="card-body d-flex">
                    @if ($book->cover_image)
                        <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" style="width: 100px; height: 150px; object-fit: cover; margin-right: 15px;">
                    @else
                        <img src="{{ asset('images/default-cover.jpg') }}" alt="Default Cover" style="width: 100px; height: 150px; object-fit: cover; margin-right: 15px;">
                    @endif
                    <div>
                        <h3 class="card-title">{{ $book->title }}</h3>
                        <p class="card-text">Muallif: {{ $book->author }}</p>
                        <p class="card-text">Janr: {{ $book->genre }}</p>
                        <p class="card-text">Til: {{ $book->language }}</p>
                        <p class="card-text">Yil: {{ $book->year }}</p>
                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-info">O'qish</a>
                        <a href="{{ route('books.download', $book->id) }}" class="btn btn-secondary">Yuklab olish</a>
                        @auth
                            @can('update', $book)
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning">Tahrirlash</a>
                            @endcan
                            @can('delete', $book)
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('O‘chirishni tasdiqlaysizmi?')">O'chirish</button>
                                </form>
                            @endcan
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
        {{ $books->links() }}
    @endif
@endsection
