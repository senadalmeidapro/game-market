<?php

namespace App\Filament\Resources\Catalog\ProductPriceHistories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductPriceHistoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product.title')
                    ->label('Product'),
                TextEntry::make('old_price_amount')
                    ->numeric(),
                TextEntry::make('new_price_amount')
                    ->numeric(),
                TextEntry::make('changed_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('changed_at')
                    ->dateTime(),
            ]);
    }
}
