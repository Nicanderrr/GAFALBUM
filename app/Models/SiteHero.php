<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteHero extends Model
{
    protected $fillable = [
        'key',
        'image_path',
    ];

    public static function urlFor(string $key, string $fallback): string
    {
        $hero = static::where('key', $key)->first();

        return $hero ? asset(Storage::url($hero->image_path)) : $fallback;
    }
}
