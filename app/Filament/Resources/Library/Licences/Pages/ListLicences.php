<?php

namespace App\Filament\Resources\Library\Licences\Pages;

use App\Filament\Resources\Library\Licences\LicenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLicences extends ListRecords
{
    protected static string $resource = LicenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
