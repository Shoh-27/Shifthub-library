@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <h1>Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h3>Jami kitoblar: {{ $totalBooks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h3>Jami foydalanuvchilar: {{ $totalUsers }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h2>So‘nggi qo‘shilgan kitoblar</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Nomi</th>
            <th>Muallif</th>
            <th>Yuklagan</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($recentBooks as $book)
            <tr>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>{{ $book->user->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h2>So‘nggi foydalanuvchilar</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Ism</th>
            <th>Email</th>
            <th>Ro‘yxatdan o‘tgan</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($recentUsers as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d.m.Y') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.books') }}" class="btn btn-primary">Kitoblarni boshqarish</a>
    <a href="{{ route('admin.users') }}" class="btn btn-primary">Foydalanuvchilarni boshqarish</a>
@endsection
