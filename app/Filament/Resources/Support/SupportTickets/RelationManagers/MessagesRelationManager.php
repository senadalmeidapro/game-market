<?php

namespace App\Filament\Resources\Support\SupportTickets\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    protected static ?string $title = 'Fil de messages';

    protected static ?string $label = 'message';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Hidden::make('author_id')->default(fn () => auth()->id()),
            Textarea::make('body')->label('Message')->required()->rows(5)->columnSpanFull(),
            Toggle::make('is_internal')->label('Note interne')->default(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at')
            ->columns([
                TextColumn::make('author.name')->label('Auteur')->placeholder('Système'),
                TextColumn::make('body')->label('Message')->wrap()->limit(240),
                TextColumn::make('is_internal')->label('Interne')->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Oui' : 'Client visible'),
                TextColumn::make('created_at')->label('Envoyé le')->dateTime()->sortable(),
            ])
            ->headerActions([
                CreateAction::make()->label('Répondre / ajouter une note')
                    ->mutateDataUsing(fn (array $data): array => [...$data, 'author_id' => auth()->id()]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return auth()->user()?->can('tickets.handle') !== true;
    }
}
