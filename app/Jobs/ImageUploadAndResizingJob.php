<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageUploadAndResizingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $mimeType;

    public string $imageContent;

    public array $resolutions = [
        '50x50' => [50, 50],
        '640x480' => [640, 480],
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $mimeType, string $imageContent)
    {
        $this->mimeType = $mimeType;
        $this->imageContent = $imageContent;
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        $storage = Storage::disk('public');

        $path = 'fake-image-name.jpg';

        $storage->put($path, base64_decode($this->imageContent));

        $paths = [];

        foreach ($this->resolutions as $key => $resolution) {
            $image = Image::make($absolutePath = $storage->path($path))
                ->resize($resolution[0], $resolution[1])
                ->save($this->absolutePathWithResolutionKey($absolutePath, $key));

            $paths[] = $image->basename;
        }

        return $paths;
    }

    /**
     * Modify an absolute path for renaming image name with resolution key
     *
     * @param string $absoluteFilePath
     * @param string $resolutionKey
     * @return string
     */
    private function absolutePathWithResolutionKey(string $absoluteFilePath, string $resolutionKey)
    {
        [$path, $extension] = explode('.', $absoluteFilePath);

        return $path . '-' . $resolutionKey . '.' . $extension;
    }
}
