<?php

namespace App\Filament\Resources\Catalog\Products\Tables;

use App\Models\Catalog\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover')
                    ->label('Jaquette')
                    ->getStateUsing(fn (Product $record): ?string => $record->getFirstMediaUrl('cover') ?: null)
                    ->height(64),
                TextColumn::make('publisher.name')
                    ->label('Editeur')
                    ->searchable(),
                ImageColumn::make('publisher.logo_storage_key')
                    ->label('Logo editeur')
                    ->disk('public')
                    ->height(40),
                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->badge(),
                TextColumn::make('genres.name')
                    ->label('Genres')
                    ->badge(),
                TextColumn::make('media_count')
                    ->label('Medias')
                    ->counts('media')
                    ->badge(),
                TextColumn::make('title')
                    ->label('Jeu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('summary')
                    ->limit(80)
                    ->toggleable(),
                TextColumn::make('price_amount')
                    ->label('Prix')
                    ->money('XOF')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('published_at')
                    ->label('Publication')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
