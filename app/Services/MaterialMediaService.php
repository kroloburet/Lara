<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MaterialMediaService
{
    /** @var string The name of the Laravel disk. */
    protected string $diskName = 'materials';

    /** @var string The name of the meta file. */
    protected string $metaFileName = 'meta.json';

    /** @var object The material object. */
    protected object $material;

    /** @var string The path to the directory in storage, in dot notation. */
    protected string $path;

    /**
     * Service constructor.
     *
     * @param object $material The material object.
     * @param string $path The path to the directory in storage.
     */
    public function __construct(object $material, string $path)
    {
        $this->material = $material;
        $this->path = $path;
    }

    /**
     * Static factory method for convenience.
     *
     * @param object $material The material object.
     * @param string $path The path to the directory.
     * @return self
     */
    public static function make(object $material, string $path): self
    {
        return new self($material, $path);
    }

    /**
     * Retrieves a structured collection of media files from the meta file.
     *
     * @return Collection
     */
    public function get(): Collection
    {
        $disk = Storage::disk($this->diskName);
        $directory = $this->getFullDirectoryPath();
        $metaPath = $directory . '/' . $this->metaFileName;

        if (!$disk->exists($metaPath)) {
            return collect();
        }

        $metaContent = $disk->get($metaPath);
        $metaData = json_decode($metaContent, true);

        return collect($metaData['files'] ?? []);
    }

    /**
     * Processes incoming data and returns the final file collection on success.
     *
     * @param array $data Validated request data.
     * @return \Illuminate\Support\Collection|false Returns the final collection on success, false on failure.
     */
    public function set(array $data)
    {
        $disk = Storage::disk($this->diskName);
        $directory = $this->getFullDirectoryPath();

        $filesToSave = $data['files'] ?? [];
        $orderedItems = $data['order'] ? json_decode($data['order'], true) : [];
        if (!is_array($orderedItems)) {
            return false;
        }

        // Create a directory if there are new files and the directory does not exist yet
        if (!empty($filesToSave) && !$disk->exists($directory)) {
            $disk->makeDirectory($directory);
        }

        // Current meta.json files keyed by id
        $currentFiles = $this->get()->keyBy('id');

        // Collect new and existing files into separate collections in the order they are received
        $newFilesCollection = collect();
        $existingFilesCollection = collect();

        foreach ($orderedItems as $item) {
            if (!isset($item['id'], $item['name'])) {
                continue;
            }

            $id = $item['id'];

            // New temp files have ids starting with 'temp_'
            if (Str::startsWith($id, 'temp_') && isset($filesToSave[$id]) && $filesToSave[$id] instanceof UploadedFile) {
                $file = $filesToSave[$id];
                $originalName = $file->getClientOriginalName();
                $permanentId = md5($originalName);

                // Save the file under its original name
                $disk->putFileAs($directory, $file, $originalName);
                $filePath = ltrim($directory, '/') . '/' . $originalName; // without leading slash for URL pasting

                $newFilesCollection->push([
                    'id'   => $permanentId,
                    'type' => $this->getFileType($file->getMimeType(), $originalName),
                    'name' => $originalName,
                    'url'  => "/uploads/materials/{$filePath}",
                ]);
            }
            // Take existing files from meta.json (if found)
            else if ($currentFiles->has($id)) {
                $existingFile = $currentFiles->get($id);
                $existingFile['url'] = "/uploads/materials/" . ltrim($directory, '/') . '/' . $existingFile['name'];
                unset($existingFile['previewContent']);
                $existingFilesCollection->push($existingFile);
            }
        }

        // Final order: new files (in order of orderedItems) first, then existing files
        $finalOrderedFiles = $newFilesCollection->concat($existingFilesCollection);

        // Delete physical files that are no longer in the final list
        $finalFileNames   = $finalOrderedFiles->pluck('name')->all();
        $currentFileNames = $currentFiles->pluck('name')->all();
        $filesToDelete    = array_diff($currentFileNames, $finalFileNames);

        foreach ($filesToDelete as $fileName) {
            $disk->delete($directory . '/' . $fileName);
        }

        // Unique by id and create meta.json
        $uniqueFiles = $finalOrderedFiles->keyBy('id');
        $metaData = ['files' => $uniqueFiles->values()->all()];
        $metaPath = $directory . '/' . $this->metaFileName;
        $disk->put($metaPath, json_encode($metaData, JSON_PRETTY_PRINT));

        // Delete the directory if there are no files and the directory is empty
        if ($uniqueFiles->isEmpty() && $disk->exists($directory)) {
            $disk->deleteDirectory($directory);
        }

        // Return an updated collection of files according to the current meta.json
        return $this->get();
    }


    /**
     * Renames a specific media file and updates its metadata.
     *
     * @param string $id The unique ID of the file.
     * @param string $oldName The original full filename.
     * @param string $newName The new full filename.
     * @return Collection|false The updated collection of files on success, or false on failure.
     */
    public function rename(string $id, string $oldName, string $newName)
    {
        $disk = Storage::disk($this->diskName);
        $directory = $this->getFullDirectoryPath();

        $oldPath = $directory . '/' . $oldName;
        $newPath = $directory . '/' . $newName;

        // 1. Check if the original file exists and the new name is not already taken
        if (!$disk->exists($oldPath) || $disk->exists($newPath)) {
            return false;
        }

        // 2. Rename the physical file
        if (!$disk->move($oldPath, $newPath)) {
            return false;
        }

        // 3. Update the metadata in meta.json
        $metaPath = $directory . '/' . $this->metaFileName;
        if (!$disk->exists($metaPath)) {
            // If meta file is missing, something is wrong. Revert the file move.
            $disk->move($newPath, $oldPath);
            return false;
        }

        $metaContent = $disk->get($metaPath);
        $metaData = json_decode($metaContent, true);
        $files = collect($metaData['files'] ?? []);

        $newPermanentId = md5($newName);

        $updatedFiles = $files->map(function ($file) use ($id, $newName, $newPath, $newPermanentId) {
            if (isset($file['id']) && $file['id'] === $id) {
                $file['id'] = $newPermanentId; // Update the ID as it's based on the name
                $file['name'] = $newName;
                $file['url'] = "/uploads/materials/{$newPath}";
            }
            return $file;
        });

        $metaData['files'] = $updatedFiles->values()->all();
        $disk->put($metaPath, json_encode($metaData, JSON_PRETTY_PRINT));

        // 4. Return the complete, updated list of files
        return $this->get();
    }

    /**
     * Returns the full path to the storage directory.
     *
     * @return string
     */
    protected function getFullDirectoryPath(): string
    {
        $normalizedPath = str_replace('.', '/', $this->path);
        return "/{$this->material->storage}/media/{$normalizedPath}";
    }

    /**
     * Determines the file type based on the MIME type or extension.
     *
     * @param string|null $mimeType
     * @param string|null $fileName
     * @return string
     */
    protected function getFileType(?string $mimeType, ?string $fileName = ''): string
    {
        if (str_starts_with((string) $mimeType, 'image')) return 'image';
        if (str_starts_with((string) $mimeType, 'video')) return 'video';
        if (Str::endsWith($fileName, '.pdf')) return 'pdf';
        return 'document';
    }
}
