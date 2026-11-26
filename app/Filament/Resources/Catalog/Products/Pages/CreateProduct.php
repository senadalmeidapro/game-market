<?php

namespace App\Filament\Resources\Catalog\Products\Pages;

use App\Filament\Resources\Catalog\Products\ProductResource;
use App\Filament\Resources\Catalog\Products\Schemas\ProductCreateForm;
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
        foreach ($this->data['media_uploads'] ?? [] as $media) {
            if (blank($media['file'] ?? null)) {
                continue;
            }

            $this->record
                ->addMediaFromDisk($media['file'], 'local')
                ->usingName($media['name'])
                ->toMediaCollection($media['collection_name']);
        }
    }

    protected function getRedirectUrl(): string
    {
        return ProductResource::getUrl('edit', ['record' => $this->record]);
    }
}
