<?php

namespace App\Filament\Resources\Support\SupportMessages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SupportMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ticket_id')
                    ->relationship('ticket', 'id')
                    ->required(),
                Select::make('author_id')
                    ->relationship('author', 'name'),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_internal')
                    ->required(),
            ]);
    }
}
