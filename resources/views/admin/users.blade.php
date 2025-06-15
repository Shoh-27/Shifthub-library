@extends('layouts.app')

@section('title', 'Foydalanuvchilarni boshqarish')

@section('content')
    <h1>Foydalanuvchilarni boshqarish</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Ism</th>
            <th>Email</th>
            <th>Admin</th>
            <th>Ro‘yxatdan o‘tgan</th>
            <th>Amallar</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->is_admin ? 'Ha' : 'Yo‘q' }}</td>
                <td>{{ $user->created_at->format('d.m.Y') }}</td>
                <td>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('O‘chirishni tasdiqlaysizmi?')" {{ $user->is_admin ? 'disabled' : '' }}>O‘chirish</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
@endsection
