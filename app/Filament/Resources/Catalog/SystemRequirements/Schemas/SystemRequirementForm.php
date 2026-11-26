<?php

namespace App\Filament\Resources\Catalog\SystemRequirements\Schemas;

use App\Enums\Catalog\RequirementTier;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SystemRequirementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'title')
                    ->required(),
                Select::make('platform_id')
                    ->relationship('platform', 'name')
                    ->required(),
                Select::make('tier')
                    ->options(RequirementTier::class)
                    ->required(),
                TextInput::make('cpu'),
                TextInput::make('ram'),
                TextInput::make('gpu'),
                TextInput::make('storage'),
                TextInput::make('os_version'),
            ]);
    }
}
