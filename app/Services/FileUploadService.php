<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    protected $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.default');
    }

    public function uploadImage($file, $folder = 'images')
    {
        if (!$file) return null;

        $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();

        $path = Storage::disk($this->disk)->putFileAs($folder, $file, $fileName, 'public');

        return Storage::disk($this->disk)->url($path);
    }

    public function uploadFile($file, $folder = 'files')
    {
        if (!$file) return null;

        $fileName = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();

        $path = Storage::disk($this->disk)->putFileAs($folder, $file, $fileName, 'public');

        return Storage::disk($this->disk)->url($path);
    }

    public function deleteFile($fileUrl)
    {
        if (!$fileUrl) return false;

        $path = parse_url($fileUrl, PHP_URL_PATH);
        $path = ltrim($path, '/');

        return Storage::disk($this->disk)->delete($path);
    }

    public function replaceFile($oldFile, $newFile, $folder = 'files')
    {
        if ($oldFile) {
            $this->deleteFile($oldFile);
        }

        return $this->uploadFile($newFile, $folder);
    }
}