<?php

namespace App\Filament\Resources\Catalog\Products\RelationManagers;

use App\Enums\Catalog\ProductFileKind;
use App\Enums\Catalog\ProductFileStatus;
use App\Enums\Catalog\ProductVersionStatus;
use App\Models\Catalog\ProductVersion;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    protected static ?string $title = 'Fichiers';

    protected static ?string $label = 'version';

    protected static ?string $pluralLabel = 'versions et fichiers';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('version_number')
                    ->label('Numero de version')
                    ->required(),
                Select::make('status')
                    ->label('Statut')
                    ->options(ProductVersionStatus::class)
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('released_at')
                    ->label('Date de sortie'),
                Toggle::make('is_latest')
                    ->label('Version actuelle')
                    ->default(false),
                RichEditor::make('release_notes')
                    ->label('Notes de version')
                    ->columnSpanFull(),
                Repeater::make('files')
                    ->label('Fichiers de cette version')
                    ->relationship()
                    ->schema([
                        FileUpload::make('storage_key')
                            ->label('Fichier du jeu')
                            ->disk('local')
                            ->directory('game-builds')
                            ->visibility('private')
                            ->storeFileNamesIn('filename')
                            ->required(),
                        Select::make('platform_id')
                            ->label('Systeme')
                            ->relationship('platform', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('kind')
                            ->label('Type de fichier')
                            ->options(ProductFileKind::class)
                            ->required(),
                        Select::make('status')
                            ->label('Statut du fichier')
                            ->options(ProductFileStatus::class)
                            ->default(ProductFileStatus::Uploaded->value)
                            ->required(),
                        TextInput::make('sha256')
                            ->label('Empreinte SHA-256')
                            ->maxLength(64),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->addActionLabel('Ajouter un fichier')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('version_number')
                    ->label('Version')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('files_count')
                    ->label('Fichiers')
                    ->counts('files')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge(),
                TextColumn::make('released_at')
                    ->label('Date de sortie')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('is_latest')
                    ->label('Actuelle')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Oui' : 'Non')
                    ->badge(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Ajouter une version et ses fichiers'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (ProductVersion $record): bool => ! $record->files()->exists()),
            ]);
    }

    public function isReadOnly(): bool
    {
        return parent::isReadOnly() || auth()->user()?->can('catalog.manage') !== true;
    }
}
