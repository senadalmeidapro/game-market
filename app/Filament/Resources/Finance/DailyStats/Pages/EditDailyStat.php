<?php

namespace App\Filament\Resources\Finance\DailyStats\Pages;

use App\Filament\Resources\Finance\DailyStats\DailyStatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyStat extends EditRecord
{
    protected static string $resource = DailyStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
