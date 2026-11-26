<?php

namespace App\Filament\Resources\Library\Licences\Pages;

use App\Filament\Resources\Library\Licences\LicenceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLicence extends ViewRecord
{
    protected static string $resource = LicenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
