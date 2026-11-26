<?php

namespace App\Filament\Resources\Catalog\Products\Schemas;

use App\Models\Catalog\Product;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('cover')
                    ->label('Jaquette principale')
                    ->state(fn (Product $record): ?string => $record->getFirstMediaUrl('cover') ?: null)
                    ->height(180),
                TextEntry::make('publisher.name')
                    ->label('Editeur')
                    ->placeholder('-'),
                ImageEntry::make('publisher.logo_storage_key')
                    ->label('Logo editeur')
                    ->disk('public')
                    ->height(64),
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
                TextEntry::make('primary_color')
                    ->label('Couleur principale')
                    ->badge(),
                TextEntry::make('secondary_color')
                    ->label('Couleur secondaire')
                    ->badge(),
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
