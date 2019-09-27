<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tagmap extends Model
{
    public function image()
    {
        $this->belongsTo(Image::class);
    }

    public function tag()
    {
        $this->hasOne(Tag::class);
    }
}
