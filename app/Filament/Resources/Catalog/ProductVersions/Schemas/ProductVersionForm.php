<?php

namespace App\Filament\Resources\Catalog\ProductVersions\Schemas;

use App\Enums\Catalog\ProductVersionStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductVersionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Produit')
                    ->relationship('product', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('version_number')
                    ->required(),
                RichEditor::make('release_notes')
                    ->columnSpanFull(),
                DateTimePicker::make('released_at'),
                Select::make('status')
                    ->options(ProductVersionStatus::class)
                    ->default('draft')
                    ->required(),
                Toggle::make('is_latest')
                    ->label('Version actuelle')
                    ->required(),
            ]);
    }
}
