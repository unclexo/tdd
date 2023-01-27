<?php

namespace Tests\Feature;

use App\Jobs\ImageUploadAndResizingJob;
use App\Models\User;
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

        Storage::disk('public')->assertExists($job->handle());
    }
}
