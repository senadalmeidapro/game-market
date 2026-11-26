<?php

namespace App\Filament\Resources\Catalog\ProductFiles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductFileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product_version_id')
                    ->numeric(),
                TextEntry::make('platform.name')
                    ->label('Platform')
                    ->placeholder('-'),
                TextEntry::make('kind')
                    ->badge(),
                TextEntry::make('filename'),
                TextEntry::make('storage_key'),
                TextEntry::make('size_bytes')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('sha256')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
