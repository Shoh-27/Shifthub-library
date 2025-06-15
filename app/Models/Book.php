<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title', 'author', 'genre', 'language', 'year', 'file_path', 'cover_image', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter($query, array $filters)
    {
        if ($filters['title'] ?? false) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if ($filters['language'] ?? false) {
            $query->where(function ($q) use ($filters) {
                $q->where('language', 'like', '%' . $filters['language'] . '%')
                    ->orWhere('title', 'like', '%' . ($filters['title'] ?? '') . '%');
            });
        }

        return $query;
    }
}
