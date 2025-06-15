<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $readBooks = $user->readBooks()->count();
        $uploadedBooks = $user->books()->count();
        $recentReadBooks = $user->readBooks()->latest('pivot_created_at')->take(5)->get();

        return view('user.dashboard', compact('user', 'readBooks', 'uploadedBooks', 'recentReadBooks'));
    }

    public function profile()
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $data = $request->only(['name', 'bio']);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);
        return redirect()->route('user.profile')->with('success', 'Profil yangilandi');
    }
}
