<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    private function validateData()
    {
        return request()->validate([
            'url' => 'required|active_url',
            'external_link'=> 'active_url'
        ]);
    }

    public function store()
    {
        Image::create($this->validateData());
    }

    public function show($id)
    {
        return Image::find($id);
    }

    public function update($id)
    {
        $image = Image::find($id);
        $image->update($this->validateData());
    }

    public function destroy($id)
    {
        $image = Image::find($id);
        $image->delete();
    }
}
