<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'bio', 'avatar', 'is_admin'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function readBooks()
    {
        return $this->belongsToMany(Book::class, 'user_book_reads', 'user_id', 'book_id')
            ->withTimestamps();
    }
}

