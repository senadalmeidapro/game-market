<?php

namespace App\Filament\Resources\Library\Licences\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LicenceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('product.title')
                    ->label('Product'),
                TextEntry::make('orderItem.id')
                    ->label('Order item'),
                TextEntry::make('status'),
                TextEntry::make('issued_at')
                    ->dateTime(),
                TextEntry::make('revoked_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('revoked_reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
