<?php

namespace App\Filament\Resources\Catalog\Products\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductMediaRelationManager extends RelationManager
{
    protected static string $relationship = 'media';

    protected static ?string $title = 'Medias';

    protected static ?string $label = 'media';

    protected static ?string $pluralLabel = 'medias';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom affiche')
                    ->required(),
                Select::make('collection_name')
                    ->label('Type de media')
                    ->options(self::mediaTypes())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('collection_name')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => self::mediaTypes()[$state] ?? $state)
                    ->badge()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('file_name')
                    ->label('Fichier'),
                TextColumn::make('mime_type')
                    ->label('Format'),
                TextColumn::make('size')
                    ->label('Taille')
                    ->formatStateUsing(fn (?int $state): string => $state === null ? '—' : number_format($state / 1048576, 2).' Mo'),
            ])
            ->headerActions([
                Action::make('uploadMedia')
                    ->label('Ajouter un media')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom affiche')
                            ->required(),
                        Select::make('collection_name')
                            ->label('Type de media')
                            ->options(self::mediaTypes())
                            ->live()
                            ->required(),
                        FileUpload::make('file')
                            ->label('Fichier')
                            ->disk('local')
                            ->directory('product-media')
                            ->visibility('private')
                            ->acceptedFileTypes(fn (Get $get): array => in_array($get('collection_name'), ['screenshots', 'cover'], true)
                                ? ['image/jpeg', 'image/png', 'image/webp', 'image/avif']
                                : ['video/mp4', 'video/webm', 'video/quicktime'])
                            ->required(),
                    ])
                    ->visible(fn (): bool => auth()->user()?->can('catalog.manage') === true)
                    ->action(function (array $data): void {
                        $this->getOwnerRecord()
                            ->addMediaFromDisk($data['file'], 'local')
                            ->usingName($data['name'])
                            ->toMediaCollection($data['collection_name']);
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (): bool => auth()->user()?->can('catalog.manage') === true),
                DeleteAction::make()
                    ->visible(fn (): bool => auth()->user()?->can('catalog.manage') === true),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->visible(fn (): bool => auth()->user()?->can('catalog.manage') === true),
            ]);
    }

    /** @return array<string, string> */
    private static function mediaTypes(): array
    {
        return [
            'trailers' => 'Trailer',
            'gameplay' => 'Gameplay',
            'short_plays' => 'Short play',
            'videos' => 'Video (ancien media)',
            'screenshots' => 'Capture d’ecran',
            'cover' => 'Illustration du jeu',
        ];
    }

    public function isReadOnly(): bool
    {
        return parent::isReadOnly() || auth()->user()?->can('catalog.manage') !== true;
    }
}
