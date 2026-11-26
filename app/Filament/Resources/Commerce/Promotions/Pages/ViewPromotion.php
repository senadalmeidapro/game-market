<?php

namespace App\Filament\Resources\Commerce\Promotions\Pages;

use App\Filament\Resources\Commerce\Promotions\PromotionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPromotion extends ViewRecord
{
    protected static string $resource = PromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
