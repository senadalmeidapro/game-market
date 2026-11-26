<?php

namespace App\Filament\Resources\Catalog\Products\RelationManagers;

use App\Enums\Catalog\CoverFormat;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductCoverVariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'media';

    protected static ?string $title = 'Jaquettes';

    protected static ?string $label = 'format de jaquette';

    protected static ?string $pluralLabel = 'formats de jaquette';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom affiche')
                    ->required(),
                Select::make('format')
                    ->label('Format de jaquette')
                    ->options(CoverFormat::variantOptions())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('collection_name', 'cover_variants'))
            ->columns([
                ImageColumn::make('preview')
                    ->label('Jaquette')
                    ->getStateUsing(fn (Media $record): string => $record->getUrl())
                    ->height(96),
                TextColumn::make('custom_properties.format')
                    ->label('Format')
                    ->getStateUsing(fn (Media $record): string => CoverFormat::tryFrom((string) $record->getCustomProperty('format', ''))?->label() ?? '-')
                    ->badge()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('file_name')
                    ->label('Fichier'),
            ])
            ->headerActions([
                Action::make('uploadCoverVariant')
                    ->label('Ajouter une jaquette derivee')
                    ->schema([
                        Select::make('format')
                            ->label('Format de jaquette')
                            ->options(CoverFormat::variantOptions())
                            ->live()
                            ->required(),
                        FileUpload::make('file')
                            ->label('Image de jaquette')
                            ->image()
                            ->disk('local')
                            ->directory('product-media/cover-variants')
                            ->visibility('private')
                            ->acceptedFileTypes(fn (Get $get): array => CoverFormat::tryFrom((string) $get('format'))?->acceptedMimeTypes() ?? ['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                            ->rules(fn (Get $get): array => CoverFormat::tryFrom((string) $get('format'))?->validationRules() ?? [])
                            ->validationMessages(['dimensions' => 'L’image ne respecte pas les dimensions du format selectionne.'])
                            ->required(),
                    ])
                    ->visible(fn (): bool => auth()->user()?->can('catalog.manage') === true)
                    ->action(function (array $data): void {
                        $format = CoverFormat::from($data['format']);
                        $media = $this->getOwnerRecord()
                            ->addMediaFromDisk($data['file'], 'local')
                            ->usingName($format->label())
                            ->toMediaCollection('cover_variants');

                        $media->setCustomProperty('format', $format->value);
                        $media->save();
                    }),
            ])
            ->recordActions([
                Action::make('replaceVariant')
                    ->label('Remplacer l’image')
                    ->schema([
                        Select::make('format')
                            ->label('Format de jaquette')
                            ->options(CoverFormat::variantOptions())
                            ->live()
                            ->required(),
                        FileUpload::make('file')
                            ->label('Nouvelle image')
                            ->image()
                            ->disk('local')
                            ->directory('product-media/cover-variants')
                            ->visibility('private')
                            ->acceptedFileTypes(fn (Get $get): array => CoverFormat::tryFrom((string) $get('format'))?->acceptedMimeTypes() ?? ['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                            ->rules(fn (Get $get): array => CoverFormat::tryFrom((string) $get('format'))?->validationRules() ?? [])
                            ->validationMessages(['dimensions' => 'L’image ne respecte pas les dimensions du format selectionne.'])
                            ->required(),
                    ])
                    ->fillForm(fn (Media $record): array => [
                        'format' => $record->getCustomProperty('format'),
                    ])
                    ->action(function (Media $record, array $data): void {
                        $format = CoverFormat::from($data['format']);
                        $replacement = $this->getOwnerRecord()
                            ->addMediaFromDisk($data['file'], 'local')
                            ->usingName($format->label())
                            ->toMediaCollection('cover_variants');

                        $replacement->setCustomProperty('format', $format->value);
                        $replacement->save();
                        $record->delete();
                    })
                    ->visible(fn (): bool => auth()->user()?->can('catalog.manage') === true),
                DeleteAction::make()
                    ->visible(fn (): bool => auth()->user()?->can('catalog.manage') === true),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->visible(fn (): bool => auth()->user()?->can('catalog.manage') === true),
            ]);
    }

    public function isReadOnly(): bool
    {
        return parent::isReadOnly() || auth()->user()?->can('catalog.manage') !== true;
    }
}
