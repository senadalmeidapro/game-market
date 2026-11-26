<?php

namespace App\Filament\Resources\Payments\Refunds\Pages;

use App\Filament\Resources\Payments\Refunds\RefundResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRefund extends ViewRecord
{
    protected static string $resource = RefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
