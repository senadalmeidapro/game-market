<?php

namespace App\Filament\Resources\Finance\LedgerEntries\Pages;

use App\Filament\Resources\Finance\LedgerEntries\LedgerEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLedgerEntry extends CreateRecord
{
    protected static string $resource = LedgerEntryResource::class;
}
