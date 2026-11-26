<?php

namespace App\Filament\Resources\Feedback\ProductReviewComments\Schemas;

use App\Enums\Feedback\ReviewCommentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductReviewCommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_review_id')
                    ->label('Avis')
                    ->relationship('review', 'id')
                    ->searchable()
                    ->required(),
                Select::make('user_id')
                    ->label('Auteur')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->required(),
                Select::make('parent_comment_id')
                    ->label('Commentaire parent')
                    ->relationship('parent', 'id')
                    ->searchable(),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(ReviewCommentStatus::class)
                    ->default('published')
                    ->required(),
            ]);
    }
}
