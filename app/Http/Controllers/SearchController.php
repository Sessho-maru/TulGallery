<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class SearchController extends Controller
{
    public function index()
    {
        $validated = request()->validate([
            'searchTerm' => 'required'
        ]);

        $tag = Tag::search($validated['searchTerm'])->get();
        return $tag;
    }

    public function all()
    {
        $allTags = Tag::orderBy('tag_name')->get();
        return $allTags;
    }
}
