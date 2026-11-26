<?php

namespace App\Filament\Resources\Catalog\SystemRequirements\Pages;

use App\Filament\Resources\Catalog\SystemRequirements\SystemRequirementResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSystemRequirement extends ViewRecord
{
    protected static string $resource = SystemRequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
