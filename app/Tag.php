<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];
    
    public function images()
    {
        return $this->belongsToMany(Image::class);
    } 
}
