<?php

namespace App\Filament\Resources\Platform\AuditLogs;

use App\Filament\Resources\Platform\AuditLogs\Pages\CreateAuditLog;
use App\Filament\Resources\Platform\AuditLogs\Pages\EditAuditLog;
use App\Filament\Resources\Platform\AuditLogs\Pages\ListAuditLogs;
use App\Filament\Resources\Platform\AuditLogs\Pages\ViewAuditLog;
use App\Filament\Resources\Platform\AuditLogs\Schemas\AuditLogForm;
use App\Filament\Resources\Platform\AuditLogs\Schemas\AuditLogInfolist;
use App\Filament\Resources\Platform\AuditLogs\Tables\AuditLogsTable;
use App\Models\Platform\AuditLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Plateforme';

    protected static ?string $navigationLabel = 'Journal des actions';

    protected static ?string $modelLabel = 'evenement';

    protected static ?string $pluralModelLabel = 'evenements consignes';

    protected static ?string $viewPermission = 'audit.view';

    protected static ?string $managePermission = null;

    protected static ?int $navigationSort = 10;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = false;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return AuditLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AuditLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditLogsTable::configure($table);
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
            'index' => ListAuditLogs::route('/'),
            'create' => CreateAuditLog::route('/create'),
            'view' => ViewAuditLog::route('/{record}'),
            'edit' => EditAuditLog::route('/{record}/edit'),
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
