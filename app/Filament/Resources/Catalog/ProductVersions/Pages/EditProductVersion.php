<?php

namespace App\Filament\Resources\Catalog\ProductVersions\Pages;

use App\Filament\Resources\Catalog\ProductVersions\ProductVersionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProductVersion extends EditRecord
{
    protected static string $resource = ProductVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
