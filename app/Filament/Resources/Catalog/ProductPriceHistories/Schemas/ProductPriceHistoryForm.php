<?php

namespace App\Filament\Resources\Catalog\ProductPriceHistories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductPriceHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'title')
                    ->required(),
                TextInput::make('old_price_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('new_price_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('changed_by')
                    ->numeric(),
                DateTimePicker::make('changed_at')
                    ->required(),
            ]);
    }
}
