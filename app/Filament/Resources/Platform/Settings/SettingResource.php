<?php

namespace App\Filament\Resources\Platform\Settings;

use App\Filament\Resources\Platform\Settings\Pages\CreateSetting;
use App\Filament\Resources\Platform\Settings\Pages\EditSetting;
use App\Filament\Resources\Platform\Settings\Pages\ListSettings;
use App\Filament\Resources\Platform\Settings\Pages\ViewSetting;
use App\Filament\Resources\Platform\Settings\Schemas\SettingForm;
use App\Filament\Resources\Platform\Settings\Schemas\SettingInfolist;
use App\Filament\Resources\Platform\Settings\Tables\SettingsTable;
use App\Models\Platform\Setting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Plateforme';

    protected static ?string $navigationLabel = 'Parametres';

    protected static ?string $modelLabel = 'parametre';

    protected static ?string $pluralModelLabel = 'parametres';

    protected static ?string $viewPermission = 'settings.manage';

    protected static ?string $managePermission = 'settings.manage';

    protected static ?int $navigationSort = 20;

    protected static bool $allowCreate = true;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Schema $schema): Schema
    {
        return SettingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SettingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SettingsTable::configure($table);
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
            'index' => ListSettings::route('/'),
            'create' => CreateSetting::route('/create'),
            'view' => ViewSetting::route('/{record}'),
            'edit' => EditSetting::route('/{record}/edit'),
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
