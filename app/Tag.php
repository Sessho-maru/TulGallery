<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function tagmap()
    {
        $this->belongsTo(Tagmap::class);
    }
}
