@extends('layouts.app')

@section('title', 'Foydalanuvchi Dashboard')

@section('content')
    <h1>Xush kelibsiz, {{ $user->name }}!</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h3>O‘qilgan kitoblar: {{ $readBooks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h3>Yuklangan kitoblar: {{ $uploadedBooks }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h2>So‘nggi o‘qilgan kitoblar</h2>
    @if ($recentReadBooks->count() > 0)
        <table class="table">
            <thead>
            <tr>
                <th>Muqova</th>
                <th>Nomi</th>
                <th>Muallif</th>
                <th>O‘qilgan sana</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($recentReadBooks as $book)
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
                    <td>{{ $book->pivot->created_at->format('d.m.Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Hozircha o‘qilgan kitoblar yo‘q.</p>
    @endif

    <a href="{{ route('user.profile') }}" class="btn btn-primary">Profilni ko‘rish</a>
@endsection
