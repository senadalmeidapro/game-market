<?php

namespace App\Filament\Resources\Catalog\Genres\Schemas;

use App\Models\Catalog\Genre;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GenreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('parent_id')
                    ->label('Genre parent')
                    ->options(fn (?Genre $record): array => Genre::query()
                        ->whereNull('parent_id')
                        ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->nullable()
                    ->placeholder('Aucun : ce genre est un genre principal')
                    ->disabled(fn (?Genre $record): bool => (bool) $record?->children()->exists())
                    ->helperText('Laisse vide pour créer un genre principal. Les sous-genres sont rattachés à un genre principal.'),
                TextInput::make('slug')
                    ->required(),
            ]);
    }
}
