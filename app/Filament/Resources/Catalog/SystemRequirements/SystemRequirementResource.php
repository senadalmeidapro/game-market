<?php

namespace App\Filament\Resources\Catalog\SystemRequirements;

use App\Filament\Resources\Catalog\SystemRequirements\Pages\CreateSystemRequirement;
use App\Filament\Resources\Catalog\SystemRequirements\Pages\EditSystemRequirement;
use App\Filament\Resources\Catalog\SystemRequirements\Pages\ListSystemRequirements;
use App\Filament\Resources\Catalog\SystemRequirements\Pages\ViewSystemRequirement;
use App\Filament\Resources\Catalog\SystemRequirements\Schemas\SystemRequirementForm;
use App\Filament\Resources\Catalog\SystemRequirements\Schemas\SystemRequirementInfolist;
use App\Filament\Resources\Catalog\SystemRequirements\Tables\SystemRequirementsTable;
use App\Models\Catalog\SystemRequirement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SystemRequirementResource extends Resource
{
    protected static ?string $model = SystemRequirement::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    protected static ?string $navigationLabel = 'Configurations systeme';

    protected static ?string $modelLabel = 'configuration systeme';

    protected static ?string $pluralModelLabel = 'configurations systeme';

    protected static ?string $viewPermission = 'catalog.view';

    protected static ?string $managePermission = 'catalog.manage';

    protected static ?int $navigationSort = 50;

    protected static bool $allowCreate = true;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedComputerDesktop;

    protected static ?string $recordTitleAttribute = 'tier';

    public static function form(Schema $schema): Schema
    {
        return SystemRequirementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SystemRequirementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SystemRequirementsTable::configure($table);
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
            'index' => ListSystemRequirements::route('/'),
            'create' => CreateSystemRequirement::route('/create'),
            'view' => ViewSystemRequirement::route('/{record}'),
            'edit' => EditSystemRequirement::route('/{record}/edit'),
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
