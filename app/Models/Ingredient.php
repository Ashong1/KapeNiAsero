<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'unit', 'stock', 'reorder_level'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')
                    ->withPivot('quantity_needed');
    }
}