@extends('layouts.app')

@section('title', 'Kitoblarni boshqarish')

@section('content')
    <h1>Kitoblarni boshqarish</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Muqova</th>
            <th>Nomi</th>
            <th>Muallif</th>
            <th>Janr</th>
            <th>Til</th>
            <th>Yil</th>
            <th>Yuklagan</th>
            <th>Amallar</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($books as $book)
            <tr>
                <td>
                    @if ($book->cover_image)
                        <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" style="width: 50px; height: 75px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-cover.jpg') }}" alt="Default Cover" style="width: 50px; height: 75px; object-fit: cover;">
                    @endif
                </td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>{{ $book->genre }}</td>
                <td>{{ $book->language }}</td>
                <td>{{ $book->year }}</td>
                <td>{{ $book->user->name }}</td>
                <td>
                    <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-info">Ko‘rish</a>
                    <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-sm btn-warning">Tahrirlash</a>
                    <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('O‘chirishni tasdiqlaysizmi?')">O‘chirish</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $books->links() }}
@endsection
