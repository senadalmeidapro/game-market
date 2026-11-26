<?php

namespace App\Filament\Resources\Catalog\Platforms;

use App\Filament\Resources\Catalog\Platforms\Pages\CreatePlatform;
use App\Filament\Resources\Catalog\Platforms\Pages\EditPlatform;
use App\Filament\Resources\Catalog\Platforms\Pages\ListPlatforms;
use App\Filament\Resources\Catalog\Platforms\Pages\ViewPlatform;
use App\Filament\Resources\Catalog\Platforms\Schemas\PlatformForm;
use App\Filament\Resources\Catalog\Platforms\Schemas\PlatformInfolist;
use App\Filament\Resources\Catalog\Platforms\Tables\PlatformsTable;
use App\Models\Catalog\Platform;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PlatformResource extends Resource
{
    protected static ?string $model = Platform::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    protected static ?string $navigationLabel = 'Plateformes';

    protected static ?string $modelLabel = 'plateforme';

    protected static ?string $pluralModelLabel = 'plateformes';

    protected static ?string $viewPermission = 'catalog.view';

    protected static ?string $managePermission = 'catalog.manage';

    protected static ?int $navigationSort = 70;

    protected static bool $allowCreate = true;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PlatformForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PlatformInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlatformsTable::configure($table);
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
            'index' => ListPlatforms::route('/'),
            'create' => CreatePlatform::route('/create'),
            'view' => ViewPlatform::route('/{record}'),
            'edit' => EditPlatform::route('/{record}/edit'),
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
            && ! $record->productFiles()->exists()
            && ! $record->systemRequirements()->exists();
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
