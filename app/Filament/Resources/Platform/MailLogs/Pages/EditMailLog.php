<?php

namespace App\Filament\Resources\Platform\MailLogs\Pages;

use App\Filament\Resources\Platform\MailLogs\MailLogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMailLog extends EditRecord
{
    protected static string $resource = MailLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
