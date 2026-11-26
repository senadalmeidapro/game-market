<?php

namespace App\Filament\Resources\Catalog\ProductFiles;

use App\Filament\Resources\Catalog\ProductFiles\Pages\CreateProductFile;
use App\Filament\Resources\Catalog\ProductFiles\Pages\EditProductFile;
use App\Filament\Resources\Catalog\ProductFiles\Pages\ListProductFiles;
use App\Filament\Resources\Catalog\ProductFiles\Pages\ViewProductFile;
use App\Filament\Resources\Catalog\ProductFiles\Schemas\ProductFileForm;
use App\Filament\Resources\Catalog\ProductFiles\Schemas\ProductFileInfolist;
use App\Filament\Resources\Catalog\ProductFiles\Tables\ProductFilesTable;
use App\Models\Catalog\ProductFile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductFileResource extends Resource
{
    protected static ?string $model = ProductFile::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    protected static ?string $navigationLabel = 'Fichiers de distribution';

    protected static ?string $modelLabel = 'fichier';

    protected static ?string $pluralModelLabel = 'fichiers de distribution';

    protected static ?string $viewPermission = 'catalog.view';

    protected static ?string $managePermission = 'catalog.manage';

    protected static ?int $navigationSort = 40;

    protected static bool $allowCreate = true;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowUp;

    protected static ?string $recordTitleAttribute = 'filename';

    public static function form(Schema $schema): Schema
    {
        return ProductFileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductFileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductFilesTable::configure($table);
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
            'index' => ListProductFiles::route('/'),
            'create' => CreateProductFile::route('/create'),
            'view' => ViewProductFile::route('/{record}'),
            'edit' => EditProductFile::route('/{record}/edit'),
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
