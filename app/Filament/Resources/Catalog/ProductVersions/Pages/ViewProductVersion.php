<?php

namespace App\Filament\Resources\Catalog\ProductVersions\Pages;

use App\Filament\Resources\Catalog\ProductVersions\ProductVersionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProductVersion extends ViewRecord
{
    protected static string $resource = ProductVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
