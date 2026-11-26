<?php

namespace App\Filament\Resources\Catalog\Products\Pages;

use App\Enums\Catalog\CoverFormat;
use App\Filament\Resources\Catalog\Products\ProductResource;
use App\Filament\Resources\Catalog\Products\Schemas\ProductCreateForm;
use App\Services\Catalog\ProductCoverColorDetector;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public function form(Schema $schema): Schema
    {
        return ProductCreateForm::configure($schema);
    }

    protected function afterCreate(): void
    {
        if (filled($this->data['cover_upload'] ?? null)) {
            $cover = $this->record
                ->addMediaFromDisk($this->data['cover_upload'], 'local')
                ->toMediaCollection('cover');

            $cover->setCustomProperty('format', CoverFormat::LibraryCapsule->value);
            $cover->save();

            if (! $this->record->colors_are_manual) {
                $colors = app(ProductCoverColorDetector::class)->detect($cover->getPath());

                if ($colors) {
                    $this->record->forceFill($colors)->save();
                }
            }
        }

        foreach ($this->data['media_uploads'] ?? [] as $media) {
            if (blank($media['file'] ?? null)) {
                continue;
            }

            $this->record
                ->addMediaFromDisk($media['file'], 'local')
                ->usingName($media['name'])
                ->toMediaCollection($media['collection_name']);
        }

        foreach ($this->data['cover_variants'] ?? [] as $variant) {
            if (blank($variant['file'] ?? null)) {
                continue;
            }

            $coverVariant = $this->record
                ->addMediaFromDisk($variant['file'], 'local')
                ->usingName(CoverFormat::from($variant['format'])->label())
                ->toMediaCollection('cover_variants');

            $coverVariant->setCustomProperty('format', $variant['format']);
            $coverVariant->save();
        }
    }

    protected function getRedirectUrl(): string
    {
        return ProductResource::getUrl('edit', ['record' => $this->record]);
    }
}
