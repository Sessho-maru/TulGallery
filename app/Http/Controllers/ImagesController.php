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
        // $message = [
        //     'url.required' => 'Require at least 1 Image'
        // ];
        return request()->validate([
            'url' => 'required|active_url',
            'desc' => '',
        ]);
    }

    public function index()
    {
        $this->authorize('viewAny', Image::class);
        return (ImageResource::collection(request()->user()->images))->response()->setStatusCode(200);
    }

    public function store()
    {
        $validated = $this->validateData();
        $this->authorize('create', Image::class);
        

        $image = request()->user()->images()->create([
            'user_id' => request()->user()->id,
            'url' => $validated['url'],
            'description' => $validated['desc']
        ]);

        return (new ImageResource($image))->response()->setStatusCode(201);
    }

    public function show($id)
    {   
        $image = Image::find($id);

        $this->authorize('view', $image);
        return (new ImageResource($image))->response()->setStatusCode(200);
    }

    public function update($id)
    {
        $image = Image::find($id);

        $this->authorize('update', $image);
        $image->update($this->validateData());

        return (new ImageResource($image))->response()->setStatusCode(200);
    }

    public function destroy($id)
    {
        $image = Image::find($id);

        $this->authorize('delete', $image);
        $image->delete();

        return response([], 204);
    }
}
