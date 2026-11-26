<?php

namespace App\Filament\Resources\Catalog\Products\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('publisher.name')
                    ->label('Editeur')
                    ->placeholder('-'),
                TextEntry::make('categories.name')
                    ->label('Categories')
                    ->listWithLineBreaks(),
                TextEntry::make('genres.name')
                    ->label('Genres')
                    ->listWithLineBreaks(),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('summary')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('price_amount')
                    ->money('XOF'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('published_at')
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
