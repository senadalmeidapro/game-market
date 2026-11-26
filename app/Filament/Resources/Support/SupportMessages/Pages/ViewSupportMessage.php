<?php

namespace App\Filament\Resources\Support\SupportMessages\Pages;

use App\Filament\Resources\Support\SupportMessages\SupportMessageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSupportMessage extends ViewRecord
{
    protected static string $resource = SupportMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
