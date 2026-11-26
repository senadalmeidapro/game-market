<?php

namespace App\Filament\Resources\Catalog\Products\Pages;

use App\Filament\Resources\Catalog\Products\ProductResource;
use App\Filament\Resources\Catalog\Products\Schemas\ProductEditForm;
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

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
