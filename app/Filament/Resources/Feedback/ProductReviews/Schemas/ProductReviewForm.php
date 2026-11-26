<?php

namespace App\Filament\Resources\Feedback\ProductReviews\Schemas;

use App\Enums\Feedback\ReviewStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Produit')
                    ->relationship('product', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('user_id')
                    ->label('Auteur')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('rating')
                    ->required()
                    ->minValue(1)
                    ->maxValue(5)
                    ->numeric(),
                Textarea::make('body')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(ReviewStatus::class)
                    ->default('pending')
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}
