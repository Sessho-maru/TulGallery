<?php

namespace App\Http\Controllers;

use App\Image;
use App\Tag;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Image as ImageResource;
use Illuminate\Http\Request;

// echo "\n" . request()->user()->id . ' / ' . request()->user()->api_token . "\n";

class ImagesController extends Controller
{
    private function validateData()
    {
        return request()->validate([
            'url' => 'required|active_url',
            'desc' => '',
            'tags' => 'required|regex:/[[:word:]]+\/+/'
        ]);
    }

    private function urlUpdate()
    {
        return request()->validate([
            'url' => 'required|active_url',
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

        $validated['tags'] = substr($validated['tags'], 0, -1);
        $tagNames = explode("/", $validated['tags']);

        foreach ($tagNames as $tagName)
        {
            $returned = Tag::where('tag_name', $tagName)->first();

            if ($returned == null)
            {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Tag names inserted incorrectly',
                ], 422);
            }
        }
        
        $image = request()->user()->images()->create([
            'user_id' => request()->user()->id,
            'url' => $validated['url'],
            'description' => $validated['desc']
        ]);

        foreach ($tagNames as $tagName)
        {
            $tag_id = Tag::where('tag_name', $tagName)->first()->id;
            DB::insert('INSERT INTO image_tag (image_id, tag_id) VALUES (?, ?)', [$image->id, $tag_id]);
        }

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

        if (request()->flag == "url_update")
        {
            $image->update($this->urlUpdate());    
        }
        else
        {
            $image->update($this->validateData());
        }
        
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
