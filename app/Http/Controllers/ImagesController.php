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
    private function validateData()
    {
        return request()->validate([
            'url' => 'required|active_url',
            'thumbnail_url' => 'required|active_url',
            'description' => '',
            'tags' => 'required',
            'format' => ''
        ]);
    }

    private function tagVaildation($tags)
    {
        $tagNames = explode(",", $tags);
        // split given $tags(string) by ','

        foreach ($tagNames as $tagName)
        {
            $returned = Tag::where('tag_name', $tagName)->first();

            if ($returned === null)
            {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'tag names inserted incorrectly',
                ], 422);
            }
        }
        // if $tagName is cannot be found in Tag model then, return 422

        return $tagNames;
    }

    private function urlUpdate()
    {
        return request()->validate([
            'url' => 'required|active_url',
            'thumbnail_url' => 'required'
        ]);
    }

    private function postUpdate()
    {
        return request()->validate([
            'description' => ''
        ]);
    }

    public function index()
    {
        // $this->authorize('viewAny', Image::class); All Registered User can view any Images
        return (ImageResource::collection(Image::all()))->response()->setStatusCode(200);
    }

    public function index_withUser()
    {
        // $this->authorize('viewAny', Image::class); All Registered User can view any Images
        return (ImageResource::collection(User::find(request()->id)->images))->response()->setStatusCode(200);
    }

    public function index_withTags()
    {
        // dd(request()->data);
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

    public function store()
    {
        $this->authorize('create', Image::class);

        $validated = $this->validateData();

        $description = htmlspecialchars($validated['description']);
        $tags = htmlspecialchars($validated['tags']);

        $tagNames = $this->tagVaildation($tags);

        $image = request()->user()->images()->create([
            'user_id' => request()->user()->id,
            'url' => $validated['url'],
            'thumbnail_url' => $validated['thumbnail_url'],
            'description' => $validated['description'],
            'format' => $validated['format']['type']
        ]);

        foreach ($tagNames as $tagName)
        {
            $tag_id = Tag::where('tag_name', $tagName)->first()->id;
            DB::insert('INSERT INTO image_tag (image_id, tag_id) VALUES (?, ?)', [$image->id, $tag_id]);
        }
        // register vaild $tag_id into each image using image's id

        return (new ImageResource($image))->response()->setStatusCode(201);
    }

    public function show($id)
    {   
        $image = Image::find($id);
        // $this->authorize('view', $image); All Registered User can view Specific Image

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
        
        if (request()->flag == "post_update")
        {
            $validated = $this->validateData();
            $tags = htmlspecialchars($validated['tags']);

            $tagNames = $this->tagVaildation($tags);
            
            DB::delete('DELETE FROM image_tag WHERE image_id LIKE ?', [$id]);
            foreach ($tagNames as $tagName)
            {
                $tag_id = Tag::where('tag_name', $tagName)->first()->id;
                DB::insert('INSERT INTO image_tag (image_id, tag_id) VALUES (?, ?)', [$image->id, $tag_id]);
            }
            // register vaild $tag_id into each image using image's id

            $image->update($this->postUpdate());
        }

        return (new ImageResource($image))->response()->setStatusCode(200);
    }

    public function report($id)
    {
        $image = Image::find($id);
        $updated_count = ($image->reported_count) + 1;
        $image->update(['reported_count' =>  $updated_count]);

        return response($updated_count, 200);
    }

    public function destroy($id)
    {
        $image = Image::find($id);
        // $this->authorize('delete', $image);
        
        DB::delete('DELETE FROM image_tag WHERE image_id LIKE ?', [$id]);

        // dd($image->url);

        $fileName = explode('images/', $image->url);
        Storage::disk('s3')->delete('images/' . $fileName[1]);
        
        $image->delete();

        return response([], 204);
    }
}
