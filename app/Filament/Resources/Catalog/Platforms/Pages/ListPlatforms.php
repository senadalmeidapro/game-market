<?php

namespace App\Filament\Resources\Catalog\Platforms\Pages;

use App\Filament\Resources\Catalog\Platforms\PlatformResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlatforms extends ListRecords
{
    protected static string $resource = PlatformResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
