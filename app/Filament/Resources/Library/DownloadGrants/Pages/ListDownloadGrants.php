<?php

namespace App\Filament\Resources\Library\DownloadGrants\Pages;

use App\Filament\Resources\Library\DownloadGrants\DownloadGrantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDownloadGrants extends ListRecords
{
    protected static string $resource = DownloadGrantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
