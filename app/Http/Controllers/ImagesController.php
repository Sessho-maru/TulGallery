<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'url' => 'required|active_url'
            ]);

        Image::create($data);
    }
}
