<?php

namespace App\Filament\Resources\Library\DownloadGrants\Pages;

use App\Filament\Resources\Library\DownloadGrants\DownloadGrantResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDownloadGrant extends EditRecord
{
    protected static string $resource = DownloadGrantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
