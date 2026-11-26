<?php

namespace App\Filament\Resources\Payments\PaymentEvents\Pages;

use App\Filament\Resources\Payments\PaymentEvents\PaymentEventResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPaymentEvent extends EditRecord
{
    protected static string $resource = PaymentEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
