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

    // --- RELATIONSHIPS ---

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- NEW SCOPES (For Cleaner Controller Code) ---

    /**
     * Scope a query to only include completed orders.
     * Usage: Order::completed()->get();
     */
    public function scopeCompleted(Builder $query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include voided orders.
     * Usage: Order::voided()->get();
     */
    public function scopeVoided(Builder $query)
    {
        return $query->where('status', 'voided');
    }

    /**
     * Scope a query to filter by date range.
     * Usage: Order::dateRange($start, $end)->get();
     */
    public function scopeDateRange(Builder $query, $start, $end)
    {
        // Ensure we cover the full day of the end date (00:00:00 to 23:59:59)
        return $query->whereBetween('created_at', [
            Carbon::parse($start)->startOfDay(), 
            Carbon::parse($end)->endOfDay()
        ]);
    }
}