<?php

namespace Tests\Feature;

use App\Jobs\ImageUploadAndResizingJob;
use App\Models\User;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_job_is_dispatchable()
    {
        $job = app(ImageUploadAndResizingJob::class, ['data' => []]);

        $this->assertTrue(method_exists($job, '__construct'));

        $this->assertTrue(method_exists($job, 'dispatch'));

        $this->assertTrue(method_exists($job, 'handle'));
    }

    /** @test */
    public function queue_api_can_be_instructed_to_dispatch_image_upload_and_resizing_job()
    {
        $this->actingAs(User::factory()->create());

        Queue::fake();

        $this->post(route('upload.resize.via.queue'), [
            'image' => $image = UploadedFile::fake()
                ->image('image.jpg', 50, 50)
                ->mimeType('image/jpeg')
        ]);

        Queue::assertPushed(ImageUploadAndResizingJob::class);
    }

    /** @test */
    public function an_image_can_be_uploaded_and_resized_via_a_queued_job()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()
            ->image('a-large-image.jpg', 1000, 1000) // Increase width and height to upload a large image
            ->mimeType('image/jpeg');

        $data = [
            'imageContent' => base64_encode($image->getContent()),
        ];

        Storage::disk('public')->assertExists((new ImageUploadAndResizingJob($data))->handle()['resized']);
    }

    /** @test */
    public function handle_method_returns_false_on_upload_fail()
    {
        $image = UploadedFile::fake()
            ->image('image.jpg', 50, 50)
            ->mimeType('image/jpeg');

        $storage = $this->mock(Filesystem::class);

        // Make the put() method return false so that the image upload fails
        $storage->shouldReceive('put')->andReturn(false);

        $data = [
            'storage' => $storage, // Pass mocked storage object for returning false from put() method
            'imageContent' => base64_encode($image->getContent()),
        ];

        $this->assertFalse((new ImageUploadAndResizingJob($data))->handle());
    }

    /** @test */
    public function handle_method_returns_false_on_invalid_image_content()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()
            ->image('image.jpg', 50, 50)
            ->mimeType('image/jpeg');

        $data = [
            'imageContent' => $image->getContent(), // Note that image content is NOT base64 encoded
        ];

        $this->assertFalse((new ImageUploadAndResizingJob($data))->handle());
    }

    /** @test */
    public function handle_method_returns_false_on_invalid_mime_type()
    {
        $file = UploadedFile::fake()->create('file.txt', '10', 'text/plain');

        $this->assertFalse((new ImageUploadAndResizingJob([
            'imageContent' => base64_encode($file->getContent()),
        ]))->handle());
    }

    /** @test */
    public function handle_method_returns_false_on_invalid_resolutions()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()
            ->image('image.jpg', 50, 50)
            ->mimeType('image/jpeg');

        $job = new ImageUploadAndResizingJob([
            'imageContent' => base64_encode($image->getContent()),
        ]);

        $job->resolutions = [];

        $this->assertFalse($job->handle());

        $job->resolutions = [
            '50x50' => [-50, 50], // Note the negative number
            '640x480' => [640], // Note the height is not provided
        ];

        $this->assertFalse($job->handle());
    }

    /** @test */
    public function handle_method_deletes_original_image_after_resizing_it()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()
            ->image('image.jpg', 50, 50)
            ->mimeType('image/jpeg');

        $result = (new ImageUploadAndResizingJob([
            'imageContent' => base64_encode($image->getContent()),
        ]))->handle();

        Storage::disk('public')->assertMissing($result['original']);

        Storage::disk('public')->assertExists($result['resized']);
    }

    /** @test */
    public function image_information_can_be_stored_in_database_from_within_a_job()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()
            ->image('image.jpg', 50, 50)
            ->mimeType('image/jpeg');

        $result = (new ImageUploadAndResizingJob([
            'disk' => 'public',
            'type' => 'sometype',
            'model' => 'App\Models\SomeModel',
            'imageContent' => base64_encode($image->getContent())
        ]))->handle();

        $this->assertDatabaseHas('media', [
            'name' => $result['resized'],
            'model' => 'App\Models\SomeModel',
        ]);
    }
}
