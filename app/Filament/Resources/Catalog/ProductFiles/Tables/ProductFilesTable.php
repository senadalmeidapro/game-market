<?php

namespace App\Filament\Resources\Catalog\ProductFiles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductFilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_version_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('platform.name')
                    ->searchable(),
                TextColumn::make('kind')
                    ->badge()
                    ->searchable(),
                TextColumn::make('filename')
                    ->searchable(),
                TextColumn::make('storage_key')
                    ->searchable(),
                TextColumn::make('size_bytes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sha256')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
