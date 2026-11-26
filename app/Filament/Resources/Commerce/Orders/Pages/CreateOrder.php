<?php

namespace App\Filament\Resources\Commerce\Orders\Pages;

use App\Filament\Resources\Commerce\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
