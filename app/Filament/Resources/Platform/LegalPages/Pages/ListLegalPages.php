<?php

namespace App\Filament\Resources\Platform\LegalPages\Pages;

use App\Filament\Resources\Platform\LegalPages\LegalPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLegalPages extends ListRecords
{
    protected static string $resource = LegalPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
