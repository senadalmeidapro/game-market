<?php

namespace App\Filament\Resources\Catalog\Products\Schemas;

use App\Enums\Catalog\CoverFormat;
use App\Models\Catalog\Category;
use App\Models\Catalog\Genre;
use App\Models\Catalog\Product;
use App\Services\Catalog\ProductCoverColorDetector;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ...self::basicComponents(),
                ...self::aboutComponents(),
            ])
            ->columns(2);
    }

    /** @return array<Component> */
    public static function basicComponents(): array
    {
        return [
            Hidden::make('type')
                ->default('game')
                ->required(),
            TextInput::make('title')
                ->label('Titre du jeu')
                ->required()
                ->maxLength(255),
            TextInput::make('slug')
                ->label('Identifiant URL')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
            TextInput::make('price_amount')
                ->label('Prix (XOF)')
                ->required()
                ->numeric()
                ->minValue(0),
            Placeholder::make('rating_summary')
                ->label('Note moyenne')
                ->content(fn (?Product $record): string => self::ratingSummary($record)),
            Toggle::make('colors_are_manual')
                ->label('Verrouiller les couleurs personnalisées')
                ->default(false)
                ->live()
                ->afterStateUpdated(function (bool $state, Set $set, ?Product $record): void {
                    if ($state || ! $record) {
                        return;
                    }

                    $cover = $record->getFirstMedia('cover');

                    if (! $cover) {
                        return;
                    }

                    $colors = app(ProductCoverColorDetector::class)->detect($cover->getPath());

                    if (! $colors) {
                        return;
                    }

                    $set('primary_color', $colors['primary_color']);
                    $set('secondary_color', $colors['secondary_color']);
                })
                ->helperText('Activé : les couleurs sont conservées lors du remplacement de la jaquette. Désactivé : elles sont détectées automatiquement.'),
            ColorPicker::make('primary_color')
                ->label('Couleur principale')
                ->hex()
                ->nullable()
                ->rule('regex:/^#[0-9A-Fa-f]{6}$/')
                ->afterStateHydrated(function (Set $set, ?Product $record): void {
                    if (! $record || $record->colors_are_manual || ($record->primary_color && $record->secondary_color)) {
                        return;
                    }

                    $cover = $record->getFirstMedia('cover');

                    if (! $cover) {
                        return;
                    }

                    $colors = app(ProductCoverColorDetector::class)->detect($cover->getPath());

                    if (! $colors) {
                        return;
                    }

                    $set('primary_color', $colors['primary_color']);
                    $set('secondary_color', $colors['secondary_color']);
                })
                ->live()
                ->afterStateUpdated(fn (Set $set) => $set('colors_are_manual', true))
                ->helperText('Couleur dominante détectée depuis la jaquette. La modifier verrouille les deux couleurs.'),
            ColorPicker::make('secondary_color')
                ->label('Couleur secondaire')
                ->hex()
                ->nullable()
                ->rule('regex:/^#[0-9A-Fa-f]{6}$/')
                ->live()
                ->afterStateUpdated(fn (Set $set) => $set('colors_are_manual', true)),
            Placeholder::make('current_cover')
                ->label('Jaquette principale actuelle')
                ->content(fn (?Product $record): string => self::coverPreview($record))
                ->html()
                ->columnSpanFull(),
            FileUpload::make('cover_upload')
                ->label('Nouvelle jaquette principale')
                ->image()
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                ->rules(CoverFormat::LibraryCapsule->validationRules())
                ->validationMessages(['dimensions' => 'La jaquette principale doit respecter le format 2:3 et mesurer au minimum 600 x 900 pixels.'])
                ->disk('local')
                ->directory('product-media/covers')
                ->visibility('private')
                ->dehydrated(false)
                ->live()
                ->afterStateUpdated(function (?TemporaryUploadedFile $state, Set $set, Get $get): void {
                    if (! $state || (bool) $get('colors_are_manual')) {
                        return;
                    }

                    $path = $state->getRealPath();

                    if (! is_string($path)) {
                        return;
                    }

                    $colors = app(ProductCoverColorDetector::class)->detect($path);

                    if (! $colors) {
                        return;
                    }

                    $set('primary_color', $colors['primary_color']);
                    $set('secondary_color', $colors['secondary_color']);
                })
                ->helperText('Format fixe : '.CoverFormat::LibraryCapsule->label().'. Les images plus grandes sont admises si elles gardent ces proportions.')
                ->columnSpanFull(),
        ];
    }

    /** @return array<Component> */
    public static function aboutComponents(): array
    {
        return [
            Select::make('publisher_id')
                ->label('Editeur')
                ->relationship('publisher', 'name')
                ->searchable()
                ->preload(),
            Select::make('categories')
                ->label('Categories')
                ->multiple()
                ->relationship('categories', 'name', fn ($query) => $query->whereNotNull('parent_id')->with('parent'))
                ->getOptionLabelFromRecordUsing(fn (Category $record): string => $record->parent
                    ? $record->parent->name.' › '.$record->name
                    : $record->name)
                ->searchable()
                ->preload(),
            Select::make('genres')
                ->label('Genres')
                ->multiple()
                ->relationship('genres', 'name', fn ($query) => $query->with('parent'))
                ->getOptionLabelFromRecordUsing(fn (Genre $record): string => $record->parent
                    ? $record->parent->name.' › '.$record->name
                    : $record->name)
                ->searchable()
                ->preload(),
            DateTimePicker::make('published_at')
                ->label('Date de publication'),
            TextInput::make('summary')
                ->label('Resume')
                ->maxLength(500)
                ->columnSpanFull(),
            RichEditor::make('description')
                ->label('Description')
                ->columnSpanFull(),
        ];
    }

    private static function ratingSummary(?Product $record): string
    {
        if (! $record) {
            return 'La note apparaitra apres reception des premiers avis.';
        }

        $reviews = $record->reviews()->where('status', 'published');
        $count = $reviews->count();
        $average = $count > 0 ? number_format((float) $reviews->avg('rating'), 1) : '-';

        return $count > 0 ? "{$average} / 5 ({$count} avis publies)" : 'Aucun avis publie';
    }

    private static function coverPreview(?Product $record): string
    {
        $media = $record?->getFirstMedia('cover');

        if (! $media) {
            return 'Aucune jaquette principale ajoutee.';
        }

        return '<img src="'.e($media->getUrl()).'" alt="Jaquette principale" style="max-height: 200px; width: auto; border-radius: 0.5rem;">';
    }
}
