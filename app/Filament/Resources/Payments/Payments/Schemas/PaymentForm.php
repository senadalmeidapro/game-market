<?php

namespace App\Filament\Resources\Payments\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                TextInput::make('provider')
                    ->required(),
                TextInput::make('method')
                    ->required(),
                TextInput::make('provider_reference'),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('XOF'),
                TextInput::make('status')
                    ->required()
                    ->default('initiated'),
                DateTimePicker::make('paid_at'),
                Textarea::make('failure_reason')
                    ->columnSpanFull(),
            ]);
    }
}
