<?php

namespace App\Filament\Resources\Catalog\ProductPriceHistories\Pages;

use App\Filament\Resources\Catalog\ProductPriceHistories\ProductPriceHistoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProductPriceHistory extends ViewRecord
{
    protected static string $resource = ProductPriceHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
