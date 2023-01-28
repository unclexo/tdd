<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
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

    public array $allowedExtension = ['jpg', 'jpeg', 'png'];

    private ?Filesystem $storage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $mimeType, string $imageContent, ?Filesystem $storage = null)
    {
        $this->mimeType = $mimeType;
        $this->imageContent = $imageContent;
        $this->storage = $storage;
    }

    public function storage()
    {
        if (! $this->storage)
            $this->storage = Storage::disk('public');

        return $this->storage;
    }

    /**
     * Execute the job.
     *
     * @return array|bool
     */
    public function handle()
    {
        $path = 'fake-image-name.jpg';
        
        if (! $this->isAllowedExtension($this->getExtensionFromMimeType())) {
            return false;
        }

        if (! $this->storage()->put($path, base64_decode($this->imageContent))) {
            return false;
        }

        $paths = [];

        foreach ($this->resolutions as $key => $resolution) {
            $image = Image::make($absolutePath = $this->storage()->path($path))
                ->resize($resolution[0], $resolution[1])
                ->save($this->absolutePathWithResolutionKey($absolutePath, $key));

            $paths[] = $image->basename;
        }

        return $paths;
    }

    private function isAllowedExtension(string $extension)
    {
        return in_array($extension, $this->allowedExtension);
    }

    private function getExtensionFromMimeType()
    {
        if (! preg_match("/^[a-z]+\/[a-z0-9\.\+-]+$/", $this->mimeType))
            return false;

        [, $extension] = explode('/', $this->mimeType);

        return $extension;
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
