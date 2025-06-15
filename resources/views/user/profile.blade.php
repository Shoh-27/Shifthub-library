@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <h1>{{ $user->name }}ning Profili</h1>
    <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Ism</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $user->name }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" class="form-control @error('bio') is-invalid @enderror">{{ $user->bio }}</textarea>
            @error('bio')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Avatar (JPEG, PNG, JPG)</label>
            <input type="file" name="avatar" class="form-control-file @error('avatar') is-invalid @enderror">
            @error('avatar')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if ($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" style="width: 100px; height: 100px; object-fit: cover; margin-top: 10px;">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Yangilash</button>
    </form>

    <h2>O‘qilgan kitoblar</h2>
    @if ($user->readBooks->count() > 0)
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
            @foreach ($user->readBooks as $book)
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
@endsection
