<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function media()
    {
        return $this->hasMany(ImageMedia::class)->orderBy('sort_order');
    }

    public function coverMedia()
    {
        return $this->hasOne(ImageMedia::class)->oldestOfMany('sort_order');
    }

    public function getCoverPathAttribute(): string
    {
        return $this->thumbnail_path ?: ($this->coverMedia?->file_path ?: $this->file_path);
    }
}
