<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Searchable;
    
    protected $guarded = [];
    
    public function images()
    {
        return $this->belongsToMany(Image::class);
    } 
}
