<?php

namespace App\Filament\Resources\Platform\LegalPages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LegalPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                RichEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('locale')
                    ->required()
                    ->default('fr'),
            ]);
    }
}
