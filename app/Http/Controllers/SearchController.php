<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class SearchController extends Controller
{
    public function index()
    {
        $validated = request()->validate([
            'searchTerm' => 'required'
        ]);

        $user = User::search($validated['searchTerm'])->get();
        return $user;
    }
}
