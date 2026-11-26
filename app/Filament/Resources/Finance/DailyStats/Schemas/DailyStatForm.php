<?php

namespace App\Filament\Resources\Finance\DailyStats\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DailyStatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->required(),
                TextInput::make('orders')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('revenue_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('refund_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('new_customers')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('downloads')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
