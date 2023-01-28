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
        if (! $this->isFilteredResolutions() || ! $this->isValidImage())
            return false;

        $path = sprintf(
            "%s.%s",
            bin2hex(random_bytes(16)), // Apply your algorithm
            $this->getExtensionFromMimeType($this->mimeType)
        );

        if (! $this->storage()->put($path, base64_decode($this->imageContent)))
            return false;

        $paths = ['original' => $path, 'resized' => []];

        foreach ($this->resolutions as $key => $resolution) {
            $image = Image::make($absolutePath = $this->storage()->path($path))
                ->resize($resolution[0], $resolution[1])
                ->save($this->absolutePathWithResolutionKey($absolutePath, $key));

            $paths['resized'][] = $image->basename;
        }

        $this->storage()->delete($path);

        return $paths;
    }

    private function isFilteredResolutions()
    {
        return array_filter((array) $this->resolutions, function ($value, $key) {
            return is_array($value) &&
                count($value) === 2 &&
                is_string($key) &&
                $value[0] > 0 &&
                $value[1] > 0;
        }, ARRAY_FILTER_USE_BOTH);
    }

    private function isValidImage()
    {
        if (
            base64_encode(base64_decode($this->imageContent, true)) === $this->imageContent &&
            ($file = tmpfile()) &&
            fwrite($file, base64_decode($this->imageContent))
        ) {
            $mimeType = mime_content_type(stream_get_meta_data($file)['uri']);

            fclose($file);

            $extensionFromContent = $this->getExtensionFromMimeType($mimeType);

            return $this->getExtensionFromMimeType($this->mimeType) === $extensionFromContent &&
                $this->isAllowedExtension($extensionFromContent);
        }

        return false;
    }

    private function getExtensionFromMimeType(string $mimeType)
    {
        if (! preg_match("/^[a-z]+\/[a-z0-9\.\+-]+$/", $mimeType))
            return false;

        [, $extension] = explode('/', $mimeType);

        return $extension;
    }

    private function isAllowedExtension(string $extension)
    {
        return in_array($extension, $this->allowedExtension);
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
