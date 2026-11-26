<?php

namespace App\Filament\Resources\Catalog\ProductFiles\Schemas;

use App\Enums\Catalog\ProductFileKind;
use App\Enums\Catalog\ProductFileStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductFileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_version_id')
                    ->label('Version')
                    ->relationship('version', 'version_number')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('platform_id')
                    ->relationship('platform', 'name'),
                Select::make('kind')
                    ->options(ProductFileKind::class)
                    ->required(),
                TextInput::make('filename')
                    ->required(),
                TextInput::make('storage_key')
                    ->label('Chemin de stockage prive')
                    ->required(),
                TextInput::make('size_bytes')
                    ->numeric(),
                TextInput::make('sha256'),
                Select::make('status')
                    ->options(ProductFileStatus::class)
                    ->default('uploading')
                    ->required(),
            ]);
    }
}
