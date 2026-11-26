<?php

namespace App\Filament\Resources\Catalog\SystemRequirements\Pages;

use App\Filament\Resources\Catalog\SystemRequirements\SystemRequirementResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSystemRequirement extends EditRecord
{
    protected static string $resource = SystemRequirementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
