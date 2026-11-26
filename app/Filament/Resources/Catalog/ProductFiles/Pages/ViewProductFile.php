<?php

namespace App\Filament\Resources\Catalog\ProductFiles\Pages;

use App\Filament\Resources\Catalog\ProductFiles\ProductFileResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProductFile extends ViewRecord
{
    protected static string $resource = ProductFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
