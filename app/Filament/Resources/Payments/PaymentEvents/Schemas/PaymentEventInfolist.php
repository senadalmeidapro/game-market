<?php

namespace App\Filament\Resources\Payments\PaymentEvents\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentEventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('payment.id')
                    ->label('Payment')
                    ->placeholder('-'),
                TextEntry::make('provider'),
                TextEntry::make('provider_event_id'),
                IconEntry::make('signature_valid')
                    ->boolean(),
                TextEntry::make('outcome'),
                TextEntry::make('processed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
