<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <--- 1. Import this Trait

class User extends Authenticatable
{
    // <--- 2. Add HasApiTokens to this list
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',                 
        'two_factor_code',      
        'two_factor_expires_at',
        'must_change_password', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_expires_at' => 'datetime',
        'must_change_password' => 'boolean',
    ];

    /**
     * Generates a new code and saves it to the database.
     */
    public function generateTwoFactorCode()
    {
        $this->timestamps = false; // Prevent updating 'updated_at'
        $this->two_factor_code = rand(100000, 999999);
        
        // Expiry set to 3 minutes
        $this->two_factor_expires_at = now()->addMinutes(3);
        
        $this->save();
    }

    /**
     * Clears the code after successful login.
     */
    public function resetTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }
}