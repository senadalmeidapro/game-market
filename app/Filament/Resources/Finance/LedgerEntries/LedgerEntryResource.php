<?php

namespace App\Filament\Resources\Finance\LedgerEntries;

use App\Filament\Resources\Finance\LedgerEntries\Pages\CreateLedgerEntry;
use App\Filament\Resources\Finance\LedgerEntries\Pages\EditLedgerEntry;
use App\Filament\Resources\Finance\LedgerEntries\Pages\ListLedgerEntries;
use App\Filament\Resources\Finance\LedgerEntries\Pages\ViewLedgerEntry;
use App\Filament\Resources\Finance\LedgerEntries\Schemas\LedgerEntryForm;
use App\Filament\Resources\Finance\LedgerEntries\Schemas\LedgerEntryInfolist;
use App\Filament\Resources\Finance\LedgerEntries\Tables\LedgerEntriesTable;
use App\Models\Finance\LedgerEntry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LedgerEntryResource extends Resource
{
    protected static ?string $model = LedgerEntry::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Journal financier';

    protected static ?string $modelLabel = 'ecriture comptable';

    protected static ?string $pluralModelLabel = 'ecritures comptables';

    protected static ?string $viewPermission = 'finance.view';

    protected static ?string $managePermission = null;

    protected static ?int $navigationSort = 20;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = false;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return LedgerEntryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LedgerEntryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LedgerEntriesTable::configure($table);
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
            'index' => ListLedgerEntries::route('/'),
            'create' => CreateLedgerEntry::route('/create'),
            'view' => ViewLedgerEntry::route('/{record}'),
            'edit' => EditLedgerEntry::route('/{record}/edit'),
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
