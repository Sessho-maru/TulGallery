<?php

namespace App\Http\Controllers;

use App\Image;
use App\Http\Resources\Image as ImageResource;
use Illuminate\Http\Request;

// echo "\n" . request()->user()->id . ' / ' . request()->user()->api_token . "\n";

class ImagesController extends Controller
{
    private function validateData()
    {
        return request()->validate([
            'url' => 'required|active_url',
            'description' => 'max:300',
            'external_link'=> 'active_url'
        ]);
    }

    public function index()
    {
        $this->authorize('viewAny', Image::class);
        return ImageResource::collection(request()->user()->images);
    }

    public function store()
    {
        $this->authorize('create', Image::class);
        request()->user()->images()->create($this->validateData());
    }

    public function show($id)
    {   
        $image = Image::find($id);

        $this->authorize('view', $image);
        return new ImageResource($image);
    }

    public function update($id)
    {
        $this->authorize('update', Image::find($id));
        Image::find($id)->update($this->validateData());
    }

    public function destroy($id)
    {
        $this->authorize('delete', Image::find($id));
        Image::find($id)->delete();
    }
}
