<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'stripe_payment_id', 'page_count', 'is_professional',
        'target_lang', 'amount', 'status',
    ];
}
