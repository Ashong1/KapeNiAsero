<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // CHANGED: replaced 'category' with 'category_id'
    protected $fillable = ['name', 'description', 'price', 'category_id', 'image_path', 'stock'];

    // New Relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Existing Ingredients Relationship
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredients')
                    ->withPivot('quantity_needed');
    }
}