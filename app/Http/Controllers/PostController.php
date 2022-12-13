<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(PostRequest $request)
    {
        auth()->user()->posts()->create($request->validated());
    }
}
