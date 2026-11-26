<?php

namespace App\Filament\Resources\Catalog\ProductPriceHistories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductPriceHistoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.title')
                    ->searchable(),
                TextColumn::make('old_price_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('new_price_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('changed_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('changed_at')
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
