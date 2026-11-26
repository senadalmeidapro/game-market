<?php

namespace App\Filament\Resources\Library\DownloadGrants\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DownloadGrantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('licence.id')
                    ->label('Licence'),
                TextEntry::make('productFile.id')
                    ->label('Product file'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('ip_address')
                    ->placeholder('-'),
                TextEntry::make('user_agent')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('issued_at')
                    ->dateTime(),
                TextEntry::make('expires_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
