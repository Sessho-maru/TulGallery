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
            'description' => 'max:300',
            'external_link'=> 'active_url'
        ]);
    }

    private function isNotIdentical($request, $sender)
    {
        return $request != $sender;
    }

    public function index()
    {
        dd(request()->user());
        return request()->user()->images;
    }

    public function store()
    {
        request()->user()->images()->create($this->validateData());
    }

    public function show($id)
    {
        if ($this->isNotIdentical(request()->api_token, Image::find($id)->user->api_token))
        {
            return response([], 403);
        }

        return Image::find($id);
    }

    public function update($id)
    {
        if ($this->isNotIdentical(request()->api_token, Image::find($id)->user->api_token))
        {
            return response([], 403);
        }

        $validated = $this->validateData();
        Image::find($id)->update($validated);
    }

    public function destroy($id)
    {
        if ($this->isNotIdentical(request()->api_token, Image::find($id)->user->api_token))
        {
            return response([], 403);
        }

        Image::find($id)->delete();
    }
}
