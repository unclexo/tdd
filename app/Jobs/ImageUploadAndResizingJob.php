<?php

namespace App\Jobs;


use App\Models\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageUploadAndResizingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;

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
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;

        $this->storage = $data['storage'] ?? null;

        $this->imageContent = $data['imageContent'] ?? '';

        $this->allowedExtension = $data['allowedExtension'] ?? $this->allowedExtension;
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
        if (! $this->isFilteredResolutions() || ! ($extension = $this->extensionFromImageContent()))
            return false;

        if (! $this->storage()->put($imageName = $this->imageName($extension), base64_decode($this->imageContent)))
            return false;

        $paths = ['original' => $imageName, 'resized' => []];

        foreach ($this->resolutions as $key => $resolution) {
            $absoluteFilePath = $this->storage()->path($imageName);
            $absoluteFilePathWithResolutionKey = $this->absolutePathWithResolutionKey($absoluteFilePath, $key);

            Image::read($absoluteFilePath)
                ->resize($resolution[0], $resolution[1])
                ->save($this->absolutePathWithResolutionKey($absoluteFilePath, $key));

            $basename = basename($absoluteFilePathWithResolutionKey);

            // Save image info into database
            $media = Media::create([
                'user_id' => $this->data['user_id'] ?? null,
                'name' => $basename,
                'resolution' => json_encode($resolution),
                'disk' => $this->data['disk'] ?? 'public',
                'type' => $this->data['type'] ?? null,
                'model' => $this->data['model'] ?? null,
            ]);

            $paths['resized'][] = $basename;
        }

        $this->storage()->delete($imageName);

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

    private function extensionFromImageContent()
    {
        if (
            base64_encode(base64_decode($this->imageContent, true)) === $this->imageContent &&
            ($file = tmpfile()) &&
            fwrite($file, base64_decode($this->imageContent))
        ) {
            $mimeType = mime_content_type(stream_get_meta_data($file)['uri']);

            fclose($file);

            if (! $this->isAllowedExtension($extension = $this->getExtensionFromMimeType($mimeType)))
                return false;

            return $extension;
        }

        return false;
    }

    private function isAllowedExtension(string $extension)
    {
        return in_array($extension, (array) $this->allowedExtension);
    }

    private function getExtensionFromMimeType(string $mimeType)
    {
        if (! preg_match("/^[a-z]+\/[a-z0-9\.\+-]+$/", $mimeType))
            return false;

        [, $extension] = explode('/', $mimeType);

        return $extension;
    }

    private function imageName(string $extension)
    {
        return sprintf(
            "%s.%s",
            bin2hex(random_bytes(16)), // Apply your algorithm
            $extension
        );
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
