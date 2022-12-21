<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaUploaderController extends Controller
{
    public function upload()
    {
        $path = \request()->file('image')->store('common', 'public');

        // Use $path to get the image path
    }
}
