<?php

namespace App\Filament\Resources\Catalog\ProductVersions\Pages;

use App\Filament\Resources\Catalog\ProductVersions\ProductVersionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductVersion extends CreateRecord
{
    protected static string $resource = ProductVersionResource::class;
}
