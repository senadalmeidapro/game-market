<?php

namespace App\Filament\Resources\Finance\LedgerEntries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LedgerEntryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type'),
                TextEntry::make('order.id')
                    ->label('Order')
                    ->placeholder('-'),
                TextEntry::make('payment.id')
                    ->label('Payment')
                    ->placeholder('-'),
                TextEntry::make('refund.id')
                    ->label('Refund')
                    ->placeholder('-'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('currency'),
                TextEntry::make('occurred_at')
                    ->dateTime(),
                TextEntry::make('description'),
                TextEntry::make('reverses_id')
                    ->numeric()
                    ->placeholder('-'),
            ]);
    }
}
