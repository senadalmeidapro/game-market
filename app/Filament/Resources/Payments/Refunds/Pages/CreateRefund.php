<?php

namespace App\Filament\Resources\Payments\Refunds\Pages;

use App\Filament\Resources\Payments\Refunds\RefundResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRefund extends CreateRecord
{
    protected static string $resource = RefundResource::class;
}
