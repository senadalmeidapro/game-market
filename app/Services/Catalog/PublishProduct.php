<?php

namespace App\Services\Catalog;

use App\Enums\Catalog\ProductFileKind;
use App\Enums\Catalog\ProductFileStatus;
use App\Enums\Catalog\ProductVersionStatus;
use App\Enums\Catalog\RequirementTier;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductFile;
use Illuminate\Support\Facades\Storage;

class PublishProduct
{
    /** @return list<string> */
    public function blockers(Product $product): array
    {
        $issues = [];
        $product->loadMissing(['publisher', 'versions.files', 'systemRequirements']);

        if (! $product->getFirstMedia('cover')) {
            $issues[] = 'Ajouter une jaquette principale.';
        }
        if ($product->price_amount <= 0) {
            $issues[] = 'Le prix doit être supérieur à zéro.';
        }
        if (! $product->versions->contains(fn ($version) => $version->status === ProductVersionStatus::Distributable
            && $version->files->contains(fn (ProductFile $file): bool => $file->kind === ProductFileKind::Build && $this->isVerifiedFile($file)))) {
            $issues[] = 'Ajouter une version distribuable avec au moins un fichier présent et vérifié (SHA-256 valide).';
        }
        if (! $product->systemRequirements->contains(fn ($requirement) => $requirement->tier === RequirementTier::Minimum)) {
            $issues[] = 'Renseigner au moins une configuration système minimale.';
        }

        return $issues;
    }

    private function isVerifiedFile(ProductFile $file): bool
    {
        if ($file->status !== ProductFileStatus::Verified
            || blank($file->sha256)
            || ! preg_match('/^[a-f0-9]{64}$/i', $file->sha256)
            || ! Storage::disk('local')->exists($file->storage_key)) {
            return false;
        }

        $actualHash = hash_file('sha256', Storage::disk('local')->path($file->storage_key));

        return is_string($actualHash) && hash_equals(strtolower($file->sha256), $actualHash);
    }

    public function publish(Product $product): array
    {
        $issues = $this->blockers($product);

        if ($issues === []) {
            $product->forceFill(['status' => 'published', 'published_at' => $product->published_at ?? now()])->save();
        }

        return $issues;
    }
}
