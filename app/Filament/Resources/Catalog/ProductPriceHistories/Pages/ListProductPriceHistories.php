<?php

namespace App\Filament\Resources\Catalog\ProductPriceHistories\Pages;

use App\Filament\Resources\Catalog\ProductPriceHistories\ProductPriceHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductPriceHistories extends ListRecords
{
    protected static string $resource = ProductPriceHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
