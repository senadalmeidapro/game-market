<?php

namespace App\Filament\Resources\Catalog\ProductFiles\Pages;

use App\Filament\Resources\Catalog\ProductFiles\ProductFileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductFile extends CreateRecord
{
    protected static string $resource = ProductFileResource::class;
}
