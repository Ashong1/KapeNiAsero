<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // UPDATED: Added 'country' to fillable
    protected $fillable = ['name', 'contact_person', 'email', 'phone', 'country'];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}