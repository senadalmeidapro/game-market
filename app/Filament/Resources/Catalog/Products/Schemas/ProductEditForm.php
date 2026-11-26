<?php

namespace App\Filament\Resources\Catalog\Products\Schemas;

use App\Filament\Resources\Catalog\Products\RelationManagers\ProductFilesRelationManager;
use App\Filament\Resources\Catalog\Products\RelationManagers\ProductMediaRelationManager;
use App\Filament\Resources\Catalog\Products\RelationManagers\ProductSystemRequirementsRelationManager;
use App\Models\Catalog\Product;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ProductEditForm
{
    public static function configure(Schema $schema, Product $record, string $pageClass): Schema
    {
        $managerProperties = [
            'ownerRecord' => $record,
            'pageClass' => $pageClass,
        ];

        return $schema
            ->components([
                ...ProductForm::basicComponents(),
                Tabs::make('Jeu')
                    ->extraAttributes(['class' => 'gamehub-centered-tabs'])
                    ->tabs([
                        Tab::make('A propos')
                            ->schema(ProductForm::aboutComponents())
                            ->columns(2),
                        Tab::make('Configurations')
                            ->schema([
                                Livewire::make(ProductSystemRequirementsRelationManager::class, $managerProperties),
                            ]),
                        Tab::make('Medias')
                            ->schema([
                                Livewire::make(ProductMediaRelationManager::class, $managerProperties),
                            ]),
                        Tab::make('Fichiers')
                            ->schema([
                                Livewire::make(ProductFilesRelationManager::class, $managerProperties),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
