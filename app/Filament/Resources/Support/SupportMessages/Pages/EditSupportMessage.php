<?php

namespace App\Filament\Resources\Support\SupportMessages\Pages;

use App\Filament\Resources\Support\SupportMessages\SupportMessageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSupportMessage extends EditRecord
{
    protected static string $resource = SupportMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
