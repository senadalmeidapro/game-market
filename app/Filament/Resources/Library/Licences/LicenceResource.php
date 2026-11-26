<?php

namespace App\Filament\Resources\Library\Licences;

use App\Filament\Resources\Library\Licences\Pages\CreateLicence;
use App\Filament\Resources\Library\Licences\Pages\EditLicence;
use App\Filament\Resources\Library\Licences\Pages\ListLicences;
use App\Filament\Resources\Library\Licences\Pages\ViewLicence;
use App\Filament\Resources\Library\Licences\Schemas\LicenceForm;
use App\Filament\Resources\Library\Licences\Schemas\LicenceInfolist;
use App\Filament\Resources\Library\Licences\Tables\LicencesTable;
use App\Models\Library\Licence;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LicenceResource extends Resource
{
    protected static ?string $model = Licence::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Bibliotheque';

    protected static ?string $navigationLabel = 'Licences';

    protected static ?string $modelLabel = 'licence';

    protected static ?string $pluralModelLabel = 'licences';

    protected static ?string $viewPermission = 'licenses.view';

    protected static ?string $managePermission = 'licenses.revoke';

    protected static ?int $navigationSort = 10;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = false;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return LicenceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LicenceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LicencesTable::configure($table);
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
            'index' => ListLicences::route('/'),
            'create' => CreateLicence::route('/create'),
            'view' => ViewLicence::route('/{record}'),
            'edit' => EditLicence::route('/{record}/edit'),
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
