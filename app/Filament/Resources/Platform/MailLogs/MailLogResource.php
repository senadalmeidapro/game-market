<?php

namespace App\Filament\Resources\Platform\MailLogs;

use App\Filament\Resources\Platform\MailLogs\Pages\CreateMailLog;
use App\Filament\Resources\Platform\MailLogs\Pages\EditMailLog;
use App\Filament\Resources\Platform\MailLogs\Pages\ListMailLogs;
use App\Filament\Resources\Platform\MailLogs\Pages\ViewMailLog;
use App\Filament\Resources\Platform\MailLogs\Schemas\MailLogForm;
use App\Filament\Resources\Platform\MailLogs\Schemas\MailLogInfolist;
use App\Filament\Resources\Platform\MailLogs\Tables\MailLogsTable;
use App\Models\Platform\MailLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MailLogResource extends Resource
{
    protected static ?string $model = MailLog::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Plateforme';

    protected static ?string $navigationLabel = 'Journal des courriels';

    protected static ?string $modelLabel = 'courriel';

    protected static ?string $pluralModelLabel = 'journaux des courriels';

    protected static ?string $viewPermission = 'mail_logs.view';

    protected static ?string $managePermission = null;

    protected static ?int $navigationSort = 40;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = false;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'recipient';

    public static function form(Schema $schema): Schema
    {
        return MailLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MailLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MailLogsTable::configure($table);
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
            'index' => ListMailLogs::route('/'),
            'create' => CreateMailLog::route('/create'),
            'view' => ViewMailLog::route('/{record}'),
            'edit' => EditMailLog::route('/{record}/edit'),
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
