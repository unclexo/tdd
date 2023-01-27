<?php

namespace Tests\Feature;

use App\Jobs\ImageUploadAndResizingJob;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JobTest extends TestCase
{
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
}
