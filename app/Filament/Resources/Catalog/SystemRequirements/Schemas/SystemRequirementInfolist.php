<?php

namespace App\Filament\Resources\Catalog\SystemRequirements\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SystemRequirementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product.title')
                    ->label('Product'),
                TextEntry::make('platform.name')
                    ->label('Platform'),
                TextEntry::make('tier')
                    ->badge(),
                TextEntry::make('cpu')
                    ->placeholder('-'),
                TextEntry::make('ram')
                    ->placeholder('-'),
                TextEntry::make('gpu')
                    ->placeholder('-'),
                TextEntry::make('storage')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('os_version')
                    ->placeholder('-'),
            ]);
    }
}
