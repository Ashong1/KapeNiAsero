<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'subtotal',
        'discount_name',
        'discount_amount',
        'total_price',
        'cash_tendered',    // <--- Added
        'change_amount',    // <--- Added
        'payment_mode', 
        'status', 
        'order_type'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}