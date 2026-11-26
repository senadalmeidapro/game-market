<?php

namespace App\Filament\Resources\Catalog\Products\Pages;

use App\Enums\Catalog\CoverFormat;
use App\Filament\Resources\Catalog\Products\ProductResource;
use App\Filament\Resources\Catalog\Products\Schemas\ProductEditForm;
use App\Services\Catalog\ProductCoverColorDetector;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    public function form(Schema $schema): Schema
    {
        return ProductEditForm::configure($schema, $this->getRecord(), static::class);
    }

    protected function getAllRelationManagers(): array
    {
        return [];
    }

    protected function afterSave(): void
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
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
