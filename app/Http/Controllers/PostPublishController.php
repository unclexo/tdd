<?php

namespace App\Http\Controllers;

use App\Mail\PostPublished;
use App\Models\Post;

class PostPublishController extends Controller
{
    public function previewMailTemplate(int $id)
    {
        $post = Post::find($id);

        return (new PostPublished($post))->render();
    }
}
