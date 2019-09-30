<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = [];

    public function path()
    {
        return url('/imgs/' . $this->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tagmaps()
    {
        return $this->hasMany(Tagmap::class);
    }
}
