<?php

namespace App\Filament\Resources\Payments\PaymentEvents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PaymentEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('payment_id')
                    ->relationship('payment', 'id'),
                TextInput::make('provider')
                    ->required(),
                TextInput::make('provider_event_id')
                    ->required(),
                TextInput::make('payload')
                    ->required(),
                Toggle::make('signature_valid')
                    ->required(),
                TextInput::make('outcome')
                    ->required()
                    ->default('received'),
                DateTimePicker::make('processed_at'),
            ]);
    }
}
