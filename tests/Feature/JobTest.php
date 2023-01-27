<?php

namespace Tests\Feature;

use App\Jobs\ImageUploadAndResizingJob;
use App\Models\User;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
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
            app(ImageUploadAndResizingJob::class),
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
}
