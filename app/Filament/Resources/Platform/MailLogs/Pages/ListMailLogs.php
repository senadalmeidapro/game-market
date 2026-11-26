<?php

namespace App\Filament\Resources\Platform\MailLogs\Pages;

use App\Filament\Resources\Platform\MailLogs\MailLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMailLogs extends ListRecords
{
    protected static string $resource = MailLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
