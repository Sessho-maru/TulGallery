<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tagmap extends Model
{
    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function tag()
    {
        return $this->hasOne(Tag::class);
    }
}
