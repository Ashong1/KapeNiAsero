<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'modifiers'];

    // Automatically convert the JSON column to a PHP array
    protected $casts = [
        'modifiers' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}