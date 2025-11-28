<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact_person', 'email', 'phone'];

    // A supplier supplies many ingredients
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}