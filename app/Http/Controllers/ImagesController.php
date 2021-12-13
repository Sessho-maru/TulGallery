<?php

namespace App\Http\Controllers;

use App\Image;
use App\Tag;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Image as ImageResource;
use Illuminate\Http\Request;

// echo "\n" . request()->user()->id . ' / ' . request()->user()->api_token . "\n";

class ImagesController extends Controller
{
    private function validate_data()
    {
        return request()->validate([
            'url' => 'required|active_url',
            'description' => '',
            'tags' => 'required',
            'format' => 'required'
        ]);
    }

    private function post_update()
    {
        return request()->validate([
            'description' => ''
        ]);
    }

    public function index()
    {
        return (ImageResource::collection(Image::all()))->response()->setStatusCode(200);
    }

    public function show($id)
    {   
        $image = Image::find($id);
        return (new ImageResource($image))->response()->setStatusCode(200);
    }

    public function store()
    {
        $validated = $this->validate_data();

        $image = request()->user()->images()->create([
            'user_id' => request()->user()->id,
            'url' => $validated['url'],
            'description' => $validated['description'],
            'format' => $validated['format']['type']
        ]);

        $tags = explode(',', $validated['tags']);
        foreach ($tags as $tag) // register valid $tag_id into each image
        {
            $tag_id = Tag::where('tag_name', $tag)->first()->id;
            DB::insert('INSERT INTO image_tag (image_id, tag_id) VALUES (?, ?)', [$image->id, $tag_id]);
        }

        return (new ImageResource($image))->response()->setStatusCode(201);
    }

    public function update($id)
    {
        DB::delete('DELETE FROM image_tag WHERE image_id LIKE ?', [$id]);

        $image = Image::find($id);
        $validated = $this->validate_data();
        $tags = explode(',', htmlspecialchars($validated['tags']));

        foreach ($tags as $tagName) // register vaild $tag_id into each image using image's id
        {
            $tag_id = Tag::where('tag_name', $tagName)->first()->id;
            DB::insert('INSERT INTO image_tag (image_id, tag_id) VALUES (?, ?)', [$image->id, $tag_id]);
        }

        $image->update($this->post_update());
        return (new ImageResource($image))->response()->setStatusCode(200);
    }

    public function destroy($id)
    {
        $image = Image::find($id);
        
        DB::delete('DELETE FROM image_tag WHERE image_id LIKE ?', [$id]);

        $fileName = explode('imgs/', $image->url);
        Storage::disk('s3')->delete('imgs/' . $fileName[1]);
        
        $image->delete();
        return response([], 204);
    }

    // public function validate_tags()
    // {
    //     $tagNames = explode(',', request()->tags);

    //     foreach ($tagNames as $tagName)
    //     {
    //         $returned = Tag::where('tag_name', $tagName)->first();
    //         if ($returned === null) // if $tagName was not found from Tag Model, return 422
    //         {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'msg' => 'tag names inserted incorrectly',
    //             ], 422);
    //         }
    //     }

    //     return response()->json([
    //         'status' => 'OK',
    //         'msg' => 'tag validation passed',
    //     ], 200);
    // }

    public function index_by_tag()
    {
        $tags = request()->data;
        $images = [];
        $image_ids = [];

        for ($i = 0; $i < count($tags); $i++)
        {
            array_push($images, DB::select('SELECT image_id FROM image_tag WHERE tag_id LIKE (?)', [$tags[$i]]));
        }

        for ($i = 0; $i < count($images); $i++)
        {
            for ($j = 0; $j < count($images[$i]); $j++)
            {
                $image_ids[$images[$i][$j]->image_id] = "";
            }
        }

        return (ImageResource::collection(Image::findMany(array_keys($image_ids))))->response()->setStatusCode(200);
    }

    public function index_by_user()
    {
        return (ImageResource::collection(User::find(request()->id)->images))->response()->setStatusCode(200);
    }

    public function report($id)
    {
        $image = Image::find($id);
        $updated_count = ($image->reported_count) + 1;
        $image->update(['reported_count' =>  $updated_count]);
        return response($updated_count, 200);
    }

}