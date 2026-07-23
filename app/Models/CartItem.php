<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $guarded = [];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function media()
    {
        return $this->belongsTo(ImageMedia::class, 'image_media_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
