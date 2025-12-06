<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'customer_name', // <--- ADD THIS
        'subtotal',
        'discount_name',
        'discount_amount',
        'total_price',
        'cash_tendered',
        'change_amount',
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

    public function scopeCompleted(Builder $query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeVoided(Builder $query)
    {
        return $query->where('status', 'voided');
    }

    public function scopeDateRange(Builder $query, $start, $end)
    {
        return $query->whereBetween('created_at', [
            Carbon::parse($start)->startOfDay(), 
            Carbon::parse($end)->endOfDay()
        ]);
    }
}