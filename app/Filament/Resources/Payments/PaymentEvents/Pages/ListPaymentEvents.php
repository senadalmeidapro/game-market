<?php

namespace App\Filament\Resources\Payments\PaymentEvents\Pages;

use App\Filament\Resources\Payments\PaymentEvents\PaymentEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaymentEvents extends ListRecords
{
    protected static string $resource = PaymentEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
