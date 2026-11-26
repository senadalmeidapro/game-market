<?php

namespace App\Filament\Resources\Catalog\SystemRequirements\Pages;

use App\Filament\Resources\Catalog\SystemRequirements\SystemRequirementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSystemRequirements extends ListRecords
{
    protected static string $resource = SystemRequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
