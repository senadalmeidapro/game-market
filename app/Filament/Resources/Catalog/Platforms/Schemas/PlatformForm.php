<?php

namespace App\Filament\Resources\Catalog\Platforms\Schemas;

use App\Enums\Catalog\PlatformCode;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PlatformForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('code')
                    ->options(PlatformCode::class)
                    ->required(),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
