<?php

namespace App\Filament\Resources\Finance\LedgerEntries\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LedgerEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required(),
                Select::make('order_id')
                    ->relationship('order', 'id'),
                Select::make('payment_id')
                    ->relationship('payment', 'id'),
                Select::make('refund_id')
                    ->relationship('refund', 'id'),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('XOF'),
                DateTimePicker::make('occurred_at')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('reverses_id')
                    ->numeric(),
            ]);
    }
}
