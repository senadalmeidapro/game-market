<?php

namespace App\Filament\Resources\Catalog\Publishers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PublisherInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('contact_email')
                    ->placeholder('-'),
                TextEntry::make('country')
                    ->placeholder('-'),
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
