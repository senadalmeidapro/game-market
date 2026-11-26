<?php

namespace App\Filament\Resources\Platform\Settings\Pages;

use App\Filament\Resources\Platform\Settings\SettingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSetting extends ViewRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
