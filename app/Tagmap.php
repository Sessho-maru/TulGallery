<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tagmap extends Model
{
    protected $guarded = [];
    
    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
