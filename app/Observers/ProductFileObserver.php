<?php

namespace App\Observers;

use App\Enums\Catalog\ProductFileStatus;
use App\Models\Catalog\ProductFile;
use Illuminate\Support\Facades\Storage;

class ProductFileObserver
{
    public function saving(ProductFile $file): void
    {
        if (blank($file->storage_key) || ! Storage::disk('local')->exists($file->storage_key)) {
            if ($file->status === ProductFileStatus::Verified) {
                $file->status = ProductFileStatus::Failed;
            }

            return;
        }

        $path = Storage::disk('local')->path($file->storage_key);
        $size = filesize($path);
        $sha256 = hash_file('sha256', $path);
        if (! is_int($size) || ! is_string($sha256)) {
            $file->status = ProductFileStatus::Failed;

            return;
        }

        $file->size_bytes = $size;
        $file->sha256 = $sha256;
        if ($file->status === ProductFileStatus::Uploaded) {
            $file->status = ProductFileStatus::Verified;
        }
    }
}
