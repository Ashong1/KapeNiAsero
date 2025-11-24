<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_price', 'payment_mode'];

    // Relationship: An order has many items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship: An order belongs to a cashier
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}