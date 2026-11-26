<?php

namespace App\Filament\Resources\Catalog\Publishers\Pages;

use App\Filament\Resources\Catalog\Publishers\PublisherResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPublisher extends ViewRecord
{
    protected static string $resource = PublisherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
