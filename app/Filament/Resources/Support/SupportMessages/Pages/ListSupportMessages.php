<?php

namespace App\Filament\Resources\Support\SupportMessages\Pages;

use App\Filament\Resources\Support\SupportMessages\SupportMessageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupportMessages extends ListRecords
{
    protected static string $resource = SupportMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
