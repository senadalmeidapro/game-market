<?php

namespace App\Filament\Resources\Support\SupportTickets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupportTicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Client')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('order_id')
                    ->label('Commande associee')
                    ->relationship('order', 'reference')
                    ->searchable(),
                Select::make('assigned_to')
                    ->label('Assigne a')
                    ->relationship('assignedUser', 'name')
                    ->searchable(),
                TextInput::make('subject')
                    ->required(),
                Select::make('category')
                    ->options(['general' => 'General', 'technical' => 'Technique', 'refund' => 'Remboursement'])
                    ->required()
                    ->default('general'),
                Select::make('status')
                    ->options(['open' => 'Ouvert', 'pending' => 'En attente', 'resolved' => 'Resolu'])
                    ->required()
                    ->default('open'),
            ]);
    }
}
