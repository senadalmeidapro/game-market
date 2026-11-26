<?php

namespace App\Filament\Resources\Catalog\Genres\Pages;

use App\Filament\Resources\Catalog\Genres\GenreResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGenre extends ViewRecord
{
    protected static string $resource = GenreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
