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

    public function rename()
    {
        $newImageName = 'you-name-it.jpg';

        $path = \request()->file('image')->storeAs('renamed', $newImageName, 'local');

        return ['path' => $path];
    }

    public function validateUpload()
    {
        \request()->validate([
            'image' => ['required', 'mimes:jpg,png', 'size:100']
        ]);

        $newImageName = 'you-name-it.jpg';

        $path = \request()->file('image')->storeAs('validated', $newImageName, 'public');

        return ['path' => $path];
    }
}
