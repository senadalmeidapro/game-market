<?php

namespace App\Filament\Resources\Platform\AuditLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AuditLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('actor_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('action'),
                TextEntry::make('subject_type')
                    ->placeholder('-'),
                TextEntry::make('subject_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('ip_address')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime(),
            ]);
    }
}
