<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageMedia extends Model
{
    protected $table = 'image_media';

    protected $guarded = [];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
