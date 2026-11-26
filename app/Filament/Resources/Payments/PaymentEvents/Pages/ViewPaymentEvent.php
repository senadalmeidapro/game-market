<?php

namespace App\Filament\Resources\Payments\PaymentEvents\Pages;

use App\Filament\Resources\Payments\PaymentEvents\PaymentEventResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentEvent extends ViewRecord
{
    protected static string $resource = PaymentEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
