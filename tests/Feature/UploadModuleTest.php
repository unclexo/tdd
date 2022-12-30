<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
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

    /** @test */
    public function it_can_upload_multiple_files()
    {
        $this->actingAs(User::factory()->create());

        Storage::fake('public');

        $response = $this->post(route('upload.multiple'), [
            'files' => [
                UploadedFile::fake()->create('image-filename.jpg', 200, 'image/jpeg'),
                UploadedFile::fake()->create('video-filename.mp4', 1024, 'video/mp4'),
                UploadedFile::fake()->create('pdf-filename.pdf', 200, 'application/pdf'),
                // UploadedFile::fake()->create('some.mp3', 500, 'audio/mp3'),
            ],
        ]);

        Storage::disk('public')->assertExists($response->json());
    }

    /** @test */
    public function it_can_resize_uploaded_image()
    {
        $this->actingAs(User::factory()->create());

        Storage::fake('public');

        $response = $this->post(route('upload.resize'), [
            'image' => $file = UploadedFile::fake()->image('image-name.jpg', 500, 500),
        ]);

        Storage::disk('public')->assertExists($response->json('path'));

        $image = Image::make(Storage::disk('public')->path($response->json('path')));
        $this->assertEquals(300, $image->width());
        $this->assertEquals(200, $image->height());
    }

    /** @test */
    public function it_can_download_a_private_file()
    {
        $this->actingAs(User::factory()->create());

        Storage::fake('local');

        $response1 = $this->post(route('upload.private'), [
            'file' => $file = UploadedFile::fake()->create('private-file.pdf', 1024, 'application/pdf'),
        ]);

        Storage::disk('local')->assertExists($response1->json('path'));

        // This name will determine the filename that is seen by the user downloading the file
        $filename = 'download-private-file.pdf';

        $response2 = $this->get(route('upload.download', $filename));
        $response2->assertStatus(200);
        $response2->assertDownload($filename);
    }
}
