<?php

namespace App\Filament\Resources\Catalog\ProductPriceHistories\Pages;

use App\Filament\Resources\Catalog\ProductPriceHistories\ProductPriceHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProductPriceHistory extends EditRecord
{
    protected static string $resource = ProductPriceHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
