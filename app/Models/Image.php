<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
    ];

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
        return $this->belongsTo(ImageMedia::class, 'cover_media_id');
    }

    public function defaultCoverMedia()
    {
        return $this->hasOne(ImageMedia::class)->oldestOfMany('sort_order');
    }

    public function getCoverPathAttribute(): string
    {
        return $this->thumbnail_path ?: ($this->coverMedia?->file_path ?: ($this->defaultCoverMedia?->file_path ?: $this->file_path));
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }
}
