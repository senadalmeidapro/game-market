<?php

namespace App\Filament\Resources\Library\DownloadGrants;

use App\Filament\Resources\Library\DownloadGrants\Pages\CreateDownloadGrant;
use App\Filament\Resources\Library\DownloadGrants\Pages\EditDownloadGrant;
use App\Filament\Resources\Library\DownloadGrants\Pages\ListDownloadGrants;
use App\Filament\Resources\Library\DownloadGrants\Pages\ViewDownloadGrant;
use App\Filament\Resources\Library\DownloadGrants\Schemas\DownloadGrantForm;
use App\Filament\Resources\Library\DownloadGrants\Schemas\DownloadGrantInfolist;
use App\Filament\Resources\Library\DownloadGrants\Tables\DownloadGrantsTable;
use App\Models\Library\DownloadGrant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DownloadGrantResource extends Resource
{
    protected static ?string $model = DownloadGrant::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Bibliotheque';

    protected static ?string $navigationLabel = 'Historique des telechargements';

    protected static ?string $modelLabel = 'autorisation de telechargement';

    protected static ?string $pluralModelLabel = 'autorisations de telechargement';

    protected static ?string $viewPermission = 'licenses.view';

    protected static ?string $managePermission = null;

    protected static ?int $navigationSort = 20;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = false;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return DownloadGrantForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DownloadGrantInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DownloadGrantsTable::configure($table);
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
            'index' => ListDownloadGrants::route('/'),
            'create' => CreateDownloadGrant::route('/create'),
            'view' => ViewDownloadGrant::route('/{record}'),
            'edit' => EditDownloadGrant::route('/{record}/edit'),
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
