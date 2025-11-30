<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkedOrder extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'customer_note', 'cart_data'];

    // Automatically convert the JSON column to a PHP array
    protected $casts = [
        'cart_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}