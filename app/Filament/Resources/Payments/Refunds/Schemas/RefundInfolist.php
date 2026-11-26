<?php

namespace App\Filament\Resources\Payments\Refunds\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RefundInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order.id')
                    ->label('Order'),
                TextEntry::make('payment.id')
                    ->label('Payment'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('reason')
                    ->columnSpanFull(),
                TextEntry::make('status'),
                TextEntry::make('requested_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('provider_refund_reference')
                    ->placeholder('-'),
                TextEntry::make('completed_at')
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
