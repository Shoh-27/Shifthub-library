@extends('layouts.app')

@section('title', $book->title)

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($book->cover_image)
                <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" style="width: 200px; height: 300px; object-fit: cover; float: left; margin-right: 20px;">
            @else
                <img src="{{ asset('images/default-cover.jpg') }}" alt="Default Cover" style="width: 200px; height: 300px; object-fit: cover; float: left; margin-right: 20px;">
            @endif
            <h1>{{ $book->title }}</h1>
            <p>@lang('Author'): {{ $book->author }}</p>
            <p>@lang('Genre'): {{ $book->genre }}</p>
            <p>@lang('Language'): {{ $book->language }}</p>
            <p>@lang('Year'): {{ $book->year }}</p>
            <p>@lang('Uploaded by'): {{ $book->user->name }}</p>
            <a href="{{ route('books.download', $book->id) }}" class="btn btn-secondary">@lang('Download')</a>
            @auth
                @can('update', $book)
                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning">@lang('Edit')</a>
                @endcan
                @can('delete', $book)
                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('@lang('Confirm deletion?')')">@lang('Delete')</button>
                    </form>
                @endcan
            @endauth

            <hr>
            <h3>@lang('Translate Book')</h3>
            <form action="{{ route('books.translate', $book->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>@lang('Select Language')</label>
                    <select name="target_lang" class="form-control">
                        <option value="en">English (EN)</option>
                        <option value="uz">O‘zbek (UZ)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-2">@lang('Translate')</button>
            </form>
        </div>
    </div>
@endsection
