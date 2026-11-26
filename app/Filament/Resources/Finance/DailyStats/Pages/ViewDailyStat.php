<?php

namespace App\Filament\Resources\Finance\DailyStats\Pages;

use App\Filament\Resources\Finance\DailyStats\DailyStatResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDailyStat extends ViewRecord
{
    protected static string $resource = DailyStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
