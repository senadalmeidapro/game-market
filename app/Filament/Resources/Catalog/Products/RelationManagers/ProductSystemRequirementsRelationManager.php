<?php

namespace App\Filament\Resources\Catalog\Products\RelationManagers;

use App\Enums\Catalog\RequirementTier;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductSystemRequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'systemRequirements';

    protected static ?string $title = 'Configurations';

    protected static ?string $label = 'configuration';

    protected static ?string $pluralLabel = 'configurations';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('platform_id')
                    ->label('Systeme')
                    ->relationship('platform', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('tier')
                    ->label('Niveau')
                    ->options(RequirementTier::class)
                    ->required(),
                TextInput::make('os_version')
                    ->label('Version du systeme'),
                TextInput::make('cpu')
                    ->label('Processeur'),
                TextInput::make('ram')
                    ->label('Memoire vive'),
                TextInput::make('gpu')
                    ->label('Carte graphique'),
                TextInput::make('storage')
                    ->label('Espace disque'),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('platform.name')
                    ->label('Systeme')
                    ->sortable(),
                TextColumn::make('tier')
                    ->label('Niveau')
                    ->badge(),
                TextColumn::make('os_version')
                    ->label('Version OS')
                    ->placeholder('—'),
                TextColumn::make('cpu')
                    ->label('Processeur')
                    ->placeholder('—'),
                TextColumn::make('ram')
                    ->label('Memoire')
                    ->placeholder('—'),
                TextColumn::make('gpu')
                    ->label('Carte graphique')
                    ->placeholder('—'),
                TextColumn::make('storage')
                    ->label('Stockage')
                    ->placeholder('—'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Ajouter une configuration'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public function isReadOnly(): bool
    {
        return parent::isReadOnly() || auth()->user()?->can('catalog.manage') !== true;
    }
}
