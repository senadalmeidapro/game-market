<?php

namespace App\Filament\Resources\Platform\MailLogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MailLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('recipient')
                    ->required(),
                TextInput::make('template')
                    ->required(),
                TextInput::make('status')
                    ->required(),
                TextInput::make('provider_message_id'),
                Textarea::make('failure_reason')
                    ->columnSpanFull(),
                DateTimePicker::make('sent_at'),
            ]);
    }
}
