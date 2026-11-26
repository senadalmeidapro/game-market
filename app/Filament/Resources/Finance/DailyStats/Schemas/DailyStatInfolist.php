<?php

namespace App\Filament\Resources\Finance\DailyStats\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DailyStatInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('orders')
                    ->numeric(),
                TextEntry::make('revenue_amount')
                    ->numeric(),
                TextEntry::make('discount_amount')
                    ->numeric(),
                TextEntry::make('refund_amount')
                    ->numeric(),
                TextEntry::make('new_customers')
                    ->numeric(),
                TextEntry::make('downloads')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
