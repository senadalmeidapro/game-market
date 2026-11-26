<?php

namespace App\Filament\Resources\Catalog\Products\Schemas;

use App\Enums\Catalog\ProductStatus;
use App\Models\Catalog\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

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
            Select::make('status')
                ->label('Statut')
                ->options(ProductStatus::class)
                ->default('draft')
                ->required(),
            Placeholder::make('rating_summary')
                ->label('Note moyenne')
                ->content(fn (?Product $record): string => self::ratingSummary($record)),
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
                ->relationship('categories', 'name')
                ->searchable()
                ->preload(),
            Select::make('genres')
                ->label('Genres')
                ->multiple()
                ->relationship('genres', 'name')
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
}
