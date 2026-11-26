<?php

namespace App\Filament\Resources\Catalog\Categories\Schemas;

use App\Models\Catalog\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->label('Groupe de catégories')
                    ->options(fn (?Category $record): array => Category::query()
                        ->whereNull('parent_id')
                        ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->nullable()
                    ->placeholder('Aucun : ce groupe est une catégorie principale')
                    ->disabled(fn (?Category $record): bool => (bool) $record?->children()->exists())
                    ->helperText('Les catégories principales servent de groupes. Rattache les valeurs filtrables à un groupe.'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
            ]);
    }
}
