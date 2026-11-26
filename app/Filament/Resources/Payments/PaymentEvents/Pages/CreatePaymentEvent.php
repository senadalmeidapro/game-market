<?php

namespace App\Filament\Resources\Payments\PaymentEvents\Pages;

use App\Filament\Resources\Payments\PaymentEvents\PaymentEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentEvent extends CreateRecord
{
    protected static string $resource = PaymentEventResource::class;
}
