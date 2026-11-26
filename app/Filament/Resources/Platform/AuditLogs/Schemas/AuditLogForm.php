<?php

namespace App\Filament\Resources\Platform\AuditLogs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AuditLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('actor_id')
                    ->numeric(),
                TextInput::make('action')
                    ->required(),
                TextInput::make('subject_type'),
                TextInput::make('subject_id')
                    ->numeric(),
                TextInput::make('old_values'),
                TextInput::make('new_values'),
                TextInput::make('ip_address'),
            ]);
    }
}
