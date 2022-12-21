<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_upload_an_image()
    {
        $this->actingAs(User::factory()->create());

        // You may fake different disk "local" for example
        Storage::fake('public');

        $this->post(route('upload.common'), [
            'image' => $file = UploadedFile::fake()->image('image-name.jpg'),
        ]);

        Storage::disk('public')->assertExists('common/' . $file->hashName());
    }
}
