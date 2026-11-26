<?php

namespace App\Filament\Resources\Catalog\ProductFiles\Pages;

use App\Filament\Resources\Catalog\ProductFiles\ProductFileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductFiles extends ListRecords
{
    protected static string $resource = ProductFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
