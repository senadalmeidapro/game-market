<?php

namespace App\Filament\Resources\Catalog\Genres\Pages;

use App\Filament\Resources\Catalog\Genres\GenreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGenres extends ListRecords
{
    protected static string $resource = GenreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
