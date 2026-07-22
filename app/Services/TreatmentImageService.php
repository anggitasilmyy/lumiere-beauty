<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TreatmentImageService
{
    public function upload(?UploadedFile $file): ?string
    {
        if (!$file) {
            return null;
        }

        return $file->store('treatments', 'public');
    }

    public function replace(?UploadedFile $file, ?string $oldPath): ?string
    {
        if (!$file) {
            return $oldPath;
        }

        $this->delete($oldPath);

        return $this->upload($file);
    }

    public function delete(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (str_starts_with($path, 'assets/')) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function publicUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        return asset('storage/' . $path);
    }
}