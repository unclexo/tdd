<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        Storage::fake('public');

        $this->post(route('upload.common'), [
            'image' => $file = UploadedFile::fake()->image('image-name.jpg'),
        ]);

        // points to "app/public/common" dir
        Storage::disk('public')->assertExists('common/' . $file->hashName());
    }

    /** @test */
    public function uploaded_image_has_a_new_name()
    {
        $this->actingAs(User::factory()->create());

        Storage::fake('local');

        $response = $this->post(route('upload.renamed'), [
            'image' => $file = UploadedFile::fake()->image($originalName = 'image-name.jpg'),
        ]);

        // points to "app/renamed" dir
        Storage::disk('local')
            ->assertMissing('renamed/' . $originalName)
            ->assertMissing('renamed/' . $file->hashName())
            ->assertExists($response->json('path')); // Note this line
    }

    /** @test */
    public function it_can_upload_valid_image()
    {
        // Uncomment this line to check errors
        // $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        Storage::fake('public');

        $this->post(route('upload.validation'), [
            'image' => UploadedFile::fake()->create('video-filename.mp4', 200, 'video/mp4'),
        ])->assertSessionHasErrors(['image']);

        $response = $this->post(route('upload.validation'), [
            'image' => UploadedFile::fake()->create('image-name.jpg', 100, 'image/jpeg'),
        ]);

        Storage::disk('public')->assertExists($response->json('path'));
    }
}
