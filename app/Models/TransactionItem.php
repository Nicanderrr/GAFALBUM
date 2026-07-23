<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function media()
    {
        return $this->belongsTo(ImageMedia::class, 'image_media_id');
    }
}
