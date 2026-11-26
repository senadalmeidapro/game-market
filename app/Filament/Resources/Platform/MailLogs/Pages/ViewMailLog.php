<?php

namespace App\Filament\Resources\Platform\MailLogs\Pages;

use App\Filament\Resources\Platform\MailLogs\MailLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMailLog extends ViewRecord
{
    protected static string $resource = MailLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
