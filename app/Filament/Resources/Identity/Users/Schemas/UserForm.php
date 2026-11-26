<?php

namespace App\Filament\Resources\Identity\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Adresse courriel')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('Telephone')
                    ->tel(),
                Select::make('status')
                    ->options(['active' => 'Actif', 'suspended' => 'Suspendu'])
                    ->required()
                    ->default('active'),
                Select::make('roles')
                    ->label('Roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
