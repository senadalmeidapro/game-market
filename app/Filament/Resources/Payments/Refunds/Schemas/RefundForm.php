<?php

namespace App\Filament\Resources\Payments\Refunds\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RefundForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                Select::make('payment_id')
                    ->relationship('payment', 'id')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Textarea::make('reason')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('requested'),
                TextInput::make('requested_by')
                    ->numeric(),
                TextInput::make('approved_by')
                    ->numeric(),
                TextInput::make('provider_refund_reference'),
                DateTimePicker::make('completed_at'),
            ]);
    }
}
