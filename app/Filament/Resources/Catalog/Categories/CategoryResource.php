<?php

namespace App\Filament\Resources\Catalog\Categories;

use App\Filament\Resources\Catalog\Categories\Pages\CreateCategory;
use App\Filament\Resources\Catalog\Categories\Pages\EditCategory;
use App\Filament\Resources\Catalog\Categories\Pages\ListCategories;
use App\Filament\Resources\Catalog\Categories\Pages\ViewCategory;
use App\Filament\Resources\Catalog\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Catalog\Categories\Schemas\CategoryInfolist;
use App\Filament\Resources\Catalog\Categories\Tables\CategoriesTable;
use App\Models\Catalog\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    protected static ?string $navigationLabel = 'Categories';

    protected static ?string $modelLabel = 'categorie';

    protected static ?string $pluralModelLabel = 'categories';

    protected static ?string $viewPermission = 'catalog.view';

    protected static ?string $managePermission = 'catalog.manage';

    protected static ?int $navigationSort = 10;

    protected static bool $allowCreate = true;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'view' => ViewCategory::route('/{record}'),
            'edit' => EditCategory::route('/{record}/edit'),
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
        return static::$allowDelete
            && filled(static::$managePermission)
            && auth()->user()?->can(static::$managePermission) === true
            && ! $record->products()->exists()
            && ! $record->children()->exists();
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
