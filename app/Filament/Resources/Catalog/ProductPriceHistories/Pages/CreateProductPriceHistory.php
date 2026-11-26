<?php

namespace App\Filament\Resources\Catalog\ProductPriceHistories\Pages;

use App\Filament\Resources\Catalog\ProductPriceHistories\ProductPriceHistoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductPriceHistory extends CreateRecord
{
    protected static string $resource = ProductPriceHistoryResource::class;
}
