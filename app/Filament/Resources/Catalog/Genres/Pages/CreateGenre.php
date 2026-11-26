<?php

namespace App\Filament\Resources\Catalog\Genres\Pages;

use App\Filament\Resources\Catalog\Genres\GenreResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGenre extends CreateRecord
{
    protected static string $resource = GenreResource::class;
}
