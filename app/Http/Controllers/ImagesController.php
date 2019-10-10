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
        $msg = [
            'tags.regex' => 'Incorrect Tag formatting'
        ];

        return request()->validate([
            'url' => 'required|active_url',
            'description' => '',
            'tags' => 'required'
        ], $msg);
    }

    private function urlUpdate()
    {
        return request()->validate([
            'url' => 'required|active_url',
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
        // dd("STOP");
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

        // $validated['tags'] = substr($validated['tags'], 0, -1);

        $tagNames = explode(",", $validated['tags']);
        // 전달된 태그 문자열을 ',' 기준으로 분할

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
        // 분할된 태그들 중 유효하지 않는 것이 있는지 검사, 있으면 422 error 와 return

        $image = request()->user()->images()->create([
            'user_id' => request()->user()->id,
            'url' => $validated['url'],
            'description' => $validated['description']
        ]);
        // image 생성

        // 추가 할 수 있는것: if $image->url === "http://via.placeholder.com/350x150" then $image->delete() and return

        foreach ($tagNames as $tagName)
        {
            $tag_id = Tag::where('tag_name', $tagName)->first()->id;
            DB::insert('INSERT INTO image_tag (image_id, tag_id) VALUES (?, ?)', [$image->id, $tag_id]);
        }
        // 유효한 태그들을 image_tag 테이블에 등록

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

            // $validated['tags'] = substr($validated['tags'], 0, -1);
            $tagNames = explode(",", $validated['tags']);

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
            
            DB::delete('DELETE FROM image_tag WHERE image_id LIKE ?', [$id]);
        
            foreach ($tagNames as $tagName)
            {
                $tag_id = Tag::where('tag_name', $tagName)->first()->id;
                DB::insert('INSERT INTO image_tag (image_id, tag_id) VALUES (?, ?)', [$image->id, $tag_id]);
            }

            $image->update($this->postUpdate());
        }

        return (new ImageResource($image))->response()->setStatusCode(200);
    }

    public function destroy($id)
    {
        $image = Image::find($id);
        $this->authorize('delete', $image);
        
        DB::delete('DELETE FROM image_tag WHERE image_id LIKE ?', [$id]);

        $fileName = explode('images/', $image->url);
        Storage::disk('s3')->delete('images/' . $fileName[1]);
        
        $image->delete();

        return response([], 204);
    }
}
