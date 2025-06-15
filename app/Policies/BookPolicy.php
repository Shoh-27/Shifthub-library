<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Book $book)
    {
        return $user->is_admin; // Faqat adminlar tahrirlashi mumkin
    }

    public function delete(User $user, Book $book)
    {
        return $user->is_admin; // Faqat adminlar o‘chirishi mumkin
    }
}
