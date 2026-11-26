<?php

namespace App\Filament\Resources\Finance\LedgerEntries\Pages;

use App\Filament\Resources\Finance\LedgerEntries\LedgerEntryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLedgerEntry extends ViewRecord
{
    protected static string $resource = LedgerEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
