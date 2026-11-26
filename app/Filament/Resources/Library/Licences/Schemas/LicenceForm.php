<?php

namespace App\Filament\Resources\Library\Licences\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LicenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('product_id')
                    ->relationship('product', 'title')
                    ->required(),
                Select::make('order_item_id')
                    ->relationship('orderItem', 'id')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                DateTimePicker::make('issued_at')
                    ->required(),
                DateTimePicker::make('revoked_at'),
                Textarea::make('revoked_reason')
                    ->columnSpanFull(),
            ]);
    }
}
