<?php

namespace App\Filament\Resources\Catalog\ProductPriceHistories;

use App\Filament\Resources\Catalog\ProductPriceHistories\Pages\CreateProductPriceHistory;
use App\Filament\Resources\Catalog\ProductPriceHistories\Pages\EditProductPriceHistory;
use App\Filament\Resources\Catalog\ProductPriceHistories\Pages\ListProductPriceHistories;
use App\Filament\Resources\Catalog\ProductPriceHistories\Pages\ViewProductPriceHistory;
use App\Filament\Resources\Catalog\ProductPriceHistories\Schemas\ProductPriceHistoryForm;
use App\Filament\Resources\Catalog\ProductPriceHistories\Schemas\ProductPriceHistoryInfolist;
use App\Filament\Resources\Catalog\ProductPriceHistories\Tables\ProductPriceHistoriesTable;
use App\Models\Catalog\ProductPriceHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductPriceHistoryResource extends Resource
{
    protected static ?string $model = ProductPriceHistory::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    protected static ?string $navigationLabel = 'Historique des prix';

    protected static ?string $modelLabel = 'changement de prix';

    protected static ?string $pluralModelLabel = 'historique des prix';

    protected static ?string $viewPermission = 'catalog.view';

    protected static ?string $managePermission = null;

    protected static ?int $navigationSort = 45;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = false;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'product_id';

    public static function form(Schema $schema): Schema
    {
        return ProductPriceHistoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductPriceHistoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductPriceHistoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductPriceHistories::route('/'),
            'create' => CreateProductPriceHistory::route('/create'),
            'view' => ViewProductPriceHistory::route('/{record}'),
            'edit' => EditProductPriceHistory::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user !== null && (
            (filled(static::$viewPermission) && $user->can(static::$viewPermission)) ||
            (filled(static::$managePermission) && $user->can(static::$managePermission))
        );
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return static::$allowCreate && filled(static::$managePermission) && auth()->user()?->can(static::$managePermission) === true;
    }

    public static function canEdit(Model $record): bool
    {
        return static::$allowEdit && filled(static::$managePermission) && auth()->user()?->can(static::$managePermission) === true;
    }

    public static function canDelete(Model $record): bool
    {
        return static::$allowDelete && filled(static::$managePermission) && auth()->user()?->can(static::$managePermission) === true;
    }

    public static function canDeleteAny(): bool
    {
        return static::$allowDelete && filled(static::$managePermission) && auth()->user()?->can(static::$managePermission) === true;
    }
}
