<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    // ADD 'supplier_id' to this list
    protected $fillable = ['name', 'unit', 'stock', 'reorder_level', 'supplier_id'];

    // An ingredient belongs to one supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')
                    ->withPivot('quantity_needed');
    }
}