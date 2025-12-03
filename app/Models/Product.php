<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'category_id', 
        'image_path', 
        'stock'
    ];

    // Accessor for API easy display (optional)
    protected $appends = ['formatted_price'];

    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredients')
                    ->withPivot('quantity_needed');
    }
}