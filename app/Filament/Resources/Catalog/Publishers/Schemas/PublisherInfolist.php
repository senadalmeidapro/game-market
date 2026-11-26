<?php

namespace App\Filament\Resources\Catalog\Publishers\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PublisherInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                ImageEntry::make('logo_storage_key')
                    ->label('Logo')
                    ->disk('public')
                    ->height(80),
                TextEntry::make('contact_email')
                    ->placeholder('-'),
                TextEntry::make('country')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label('À propos')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('contract_status')
                    ->badge(),
                TextEntry::make('contract_storage_key')
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
