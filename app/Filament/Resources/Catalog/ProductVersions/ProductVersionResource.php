<?php

namespace App\Filament\Resources\Catalog\ProductVersions;

use App\Filament\Resources\Catalog\ProductVersions\Pages\CreateProductVersion;
use App\Filament\Resources\Catalog\ProductVersions\Pages\EditProductVersion;
use App\Filament\Resources\Catalog\ProductVersions\Pages\ListProductVersions;
use App\Filament\Resources\Catalog\ProductVersions\Pages\ViewProductVersion;
use App\Filament\Resources\Catalog\ProductVersions\Schemas\ProductVersionForm;
use App\Filament\Resources\Catalog\ProductVersions\Schemas\ProductVersionInfolist;
use App\Filament\Resources\Catalog\ProductVersions\Tables\ProductVersionsTable;
use App\Models\Catalog\ProductVersion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductVersionResource extends Resource
{
    protected static ?string $model = ProductVersion::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    protected static ?string $navigationLabel = 'Versions';

    protected static ?string $modelLabel = 'version';

    protected static ?string $pluralModelLabel = 'versions';

    protected static ?string $viewPermission = 'catalog.view';

    protected static ?string $managePermission = 'catalog.manage';

    protected static ?int $navigationSort = 30;

    protected static bool $allowCreate = true;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'version_number';

    public static function form(Schema $schema): Schema
    {
        return ProductVersionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductVersionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductVersionsTable::configure($table);
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
            'index' => ListProductVersions::route('/'),
            'create' => CreateProductVersion::route('/create'),
            'view' => ViewProductVersion::route('/{record}'),
            'edit' => EditProductVersion::route('/{record}/edit'),
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
