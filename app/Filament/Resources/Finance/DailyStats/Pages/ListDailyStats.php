<?php

namespace App\Filament\Resources\Finance\DailyStats\Pages;

use App\Filament\Resources\Finance\DailyStats\DailyStatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyStats extends ListRecords
{
    protected static string $resource = DailyStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
