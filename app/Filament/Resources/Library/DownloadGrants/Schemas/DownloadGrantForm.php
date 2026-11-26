<?php

namespace App\Filament\Resources\Library\DownloadGrants\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DownloadGrantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('licence_id')
                    ->relationship('licence', 'id')
                    ->required(),
                Select::make('product_file_id')
                    ->relationship('productFile', 'id')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('ip_address'),
                Textarea::make('user_agent')
                    ->columnSpanFull(),
                DateTimePicker::make('issued_at')
                    ->required(),
                DateTimePicker::make('expires_at')
                    ->required(),
            ]);
    }
}
