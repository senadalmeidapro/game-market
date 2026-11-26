<?php

namespace App\Filament\Resources\Catalog\ProductFiles\Pages;

use App\Filament\Resources\Catalog\ProductFiles\ProductFileResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProductFile extends EditRecord
{
    protected static string $resource = ProductFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
