<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    // Helper to get value by key with a default fallback
    public static function get($key, $default = null)
    {
        // Cache settings for performance (optional, but good practice)
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    // Helper to set value
    public static function set($key, $value)
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.{$key}"); // Clear cache on update
    }
}