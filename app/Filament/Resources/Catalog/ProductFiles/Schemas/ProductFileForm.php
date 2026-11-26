<?php

namespace App\Filament\Resources\Catalog\ProductFiles\Schemas;

use App\Enums\Catalog\ProductFileKind;
use App\Enums\Catalog\ProductFileStatus;
use App\Filament\Forms\Components\BuildFileUpload;
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
                TextInput::make('filename')->required(),
                BuildFileUpload::make('storage_key')->label('Fichier distribuable')
                    ->filenameField('filename')->sizeField('size_bytes')->sha256Field('sha256'),
                TextInput::make('size_bytes')->label('Taille (octets)')->numeric()->readOnly(),
                TextInput::make('sha256')->label('Empreinte SHA-256')->readOnly(),
                Select::make('status')
                    ->options(ProductFileStatus::class)
                    ->default('uploading')
                    ->required(),
            ]);
    }
}
