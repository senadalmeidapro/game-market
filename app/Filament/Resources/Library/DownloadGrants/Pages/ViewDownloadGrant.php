<?php

namespace App\Filament\Resources\Library\DownloadGrants\Pages;

use App\Filament\Resources\Library\DownloadGrants\DownloadGrantResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDownloadGrant extends ViewRecord
{
    protected static string $resource = DownloadGrantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
