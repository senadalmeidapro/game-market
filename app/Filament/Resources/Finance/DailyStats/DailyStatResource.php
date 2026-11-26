<?php

namespace App\Filament\Resources\Finance\DailyStats;

use App\Filament\Resources\Finance\DailyStats\Pages\CreateDailyStat;
use App\Filament\Resources\Finance\DailyStats\Pages\EditDailyStat;
use App\Filament\Resources\Finance\DailyStats\Pages\ListDailyStats;
use App\Filament\Resources\Finance\DailyStats\Pages\ViewDailyStat;
use App\Filament\Resources\Finance\DailyStats\Schemas\DailyStatForm;
use App\Filament\Resources\Finance\DailyStats\Schemas\DailyStatInfolist;
use App\Filament\Resources\Finance\DailyStats\Tables\DailyStatsTable;
use App\Models\Finance\DailyStat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DailyStatResource extends Resource
{
    protected static ?string $model = DailyStat::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Statistiques journalieres';

    protected static ?string $modelLabel = 'statistique';

    protected static ?string $pluralModelLabel = 'statistiques journalieres';

    protected static ?string $viewPermission = 'finance.view';

    protected static ?string $managePermission = null;

    protected static ?int $navigationSort = 10;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = false;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return DailyStatForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DailyStatInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DailyStatsTable::configure($table);
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
            'index' => ListDailyStats::route('/'),
            'create' => CreateDailyStat::route('/create'),
            'view' => ViewDailyStat::route('/{record}'),
            'edit' => EditDailyStat::route('/{record}/edit'),
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
