<?php

namespace Tests\Feature;

use App\Jobs\ImageUploadAndResizingJob;
use App\Models\User;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\Dispatchable;
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
        $job = new \ReflectionClass(ImageUploadAndResizingJob::class);

        $this->assertTrue(in_array(Dispatchable::class, $job->getTraitNames()));

        $this->assertTrue(method_exists(
            app(ImageUploadAndResizingJob::class, ['mimeType' => 'image/jpeg', 'imageContent' => 'content']),
            'handle'
        ));
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

        $job = new ImageUploadAndResizingJob($image->getMimeType(), base64_encode($image->getContent()));

        Storage::disk('public')->assertExists($job->handle()['resized']);
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

        // Pass mocked storage object for returning false from put() method
        $job = new ImageUploadAndResizingJob($image->getMimeType(), base64_encode($image->getContent()), $storage);

        $this->assertFalse($job->handle());
    }

    /** @test */
    public function handle_method_returns_false_on_invalid_mime_type()
    {
        $file = UploadedFile::fake()->create('file.txt', '10', 'text/plain');

        $job = new ImageUploadAndResizingJob($file->getMimeType(), base64_encode($file->getContent()));

        $this->assertFalse($job->handle());
    }

    /** @test */
    public function handle_method_returns_false_on_invalid_image_content()
    {
        $image = UploadedFile::fake()
            ->image('image.jpg', 50, 50)
            ->mimeType('image/jpeg');

        Storage::fake('public');

        // Note that the image content is NOT base64 encoded
        $job = new ImageUploadAndResizingJob($image->getMimeType(), $image->getContent());

        $this->assertFalse($job->handle());
    }

    /** @test */
    public function handle_method_returns_false_on_invalid_resolutions()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()
            ->image('image.jpg', 50, 50)
            ->mimeType('image/jpeg');

        $job = new ImageUploadAndResizingJob($image->getMimeType(), base64_encode($image->getContent()));

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

        $job = new ImageUploadAndResizingJob($image->getMimeType(), base64_encode($image->getContent()));

        $result = $job->handle();

        Storage::disk('public')->assertMissing($result['original']);

        Storage::disk('public')->assertExists($result['resized']);
    }
}
