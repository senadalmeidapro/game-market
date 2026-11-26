<?php

namespace App\Filament\Resources\Commerce\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('reference')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('promotion_id')
                    ->relationship('promotion', 'id'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('subtotal_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('tax_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('XOF'),
                TextInput::make('promotion_code_snapshot'),
                DateTimePicker::make('expires_at'),
                DateTimePicker::make('paid_at'),
            ]);
    }
}
