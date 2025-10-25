<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class UploadService
{
    public function removeMaterialStorage(string $storage): bool
    {
        if (! $storage) return false;

        $disk = Storage::disk('materials');
        return $disk->deleteDirectory($storage);
    }

    /**
     * Clear the provided directory in material storage
     *
     * @param string $storage
     * @param string $pathToDir
     * @return bool
     */
    public function clearDir(
        string $storage,
        string $pathToDir
    ): bool
    {
        if (! $storage || ! $pathToDir) return false;

        $disk = Storage::disk('materials');
        $dirPath = "$storage/$pathToDir";
        $oldFiles = $disk->files($dirPath);

        // Remove old files
        if (! empty($oldFiles)) {
            collect($oldFiles)->each(fn($path) => $disk->delete($path));
        }

        return true;
    }

    /**
     * Save new bg image remove old bg image if exist
     *
     * @param string|null $base64Data Base64 string from the request
     * @param string $storage Name of material storage directory
     * @return string URL to new bg image
     */
    public function bgImageUpload(
        string|null $base64Data,
        string $storage
    ): string
    {
        return $this->uploadImage($base64Data, $storage, 'bgImage', 'materialBgImageUrl');
    }

    /**
     * Remove bg image if exist and return url to bg image by default
     *
     * @param string $storage Name of material storage directory
     * @return string URL to bg image by default
     */
    public function bgImageDelete(
        string $storage
    ): string
    {
        return $this->deleteImage($storage, 'bgImage', 'materialBgImageUrl');
    }

    /**
     * Uploads an image and removes old files in the directory
     *
     * @param string|null $base64Data Base64 string of the image
     * @param string $storage Name of material storage directory
     * @param string $dirName Directory name (avatar or bgImage)
     * @param string $defaultUrlFunction Function to get default URL
     * @return string URL to the uploaded image
     * @throws InvalidArgumentException If base64 data or file type is invalid
     */
    private function uploadImage(
        ?string $base64Data,
        string $storage,
        string $dirName,
        string $defaultUrlFunction
    ): string
    {
        if (! $base64Data || ! $storage) {
            return $defaultUrlFunction();
        }

        // Decode Base64 data
        @list($type, $data) = explode(';', $base64Data);
        @list(, $data) = explode(',', $data);
        $data = base64_decode($data);

        if ($data === false) {
            throw new InvalidArgumentException("Base64 string is invalid.");
        }

        // Validate file type from data URI
        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $data);
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

        if (! in_array($mime, $allowedMimes)) {
            throw new InvalidArgumentException("Invalid file type: $mime");
        }

        // Define file extension from MIME type
        $extension = match ($mime) {
            'image/jpeg' => 'jpeg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => throw new InvalidArgumentException("Invalid file type: $mime"),
        };

        // Set up paths and file name
        $disk = Storage::disk('materials');
        $dirPath = "$storage/$dirName";
        $fileName = Str::random(8) . '.' . $extension;
        $pathToFile = "$dirPath/$fileName";

        // Remove old files from directory
        $this->clearDir($storage, $dirName);

        // Store new file
        $disk->put($pathToFile, $data);

        // Return the URL to the new file
        return $disk->url($pathToFile);
    }

    /**
     * Deletes an image (avatar or background) and returns the default URL
     *
     * @param string $storage Name of profile storage directory
     * @param string $dirName Directory name (avatar or bgImage)
     * @param string $defaultUrlFunction Function to get default URL
     * @return string Default URL for the image
     */
    private function deleteImage(
        string $storage,
        string $dirName,
        string $defaultUrlFunction
    ): string
    {
        $default = $defaultUrlFunction();

        if (! $storage) return $default;

        $this->clearDir($storage, $dirName);

        return $default;
    }
}
