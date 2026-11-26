<?php

namespace App\Filament\Resources\Payments\Refunds;

use App\Filament\Resources\Payments\Refunds\Pages\CreateRefund;
use App\Filament\Resources\Payments\Refunds\Pages\EditRefund;
use App\Filament\Resources\Payments\Refunds\Pages\ListRefunds;
use App\Filament\Resources\Payments\Refunds\Pages\ViewRefund;
use App\Filament\Resources\Payments\Refunds\Schemas\RefundForm;
use App\Filament\Resources\Payments\Refunds\Schemas\RefundInfolist;
use App\Filament\Resources\Payments\Refunds\Tables\RefundsTable;
use App\Models\Payments\Refund;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RefundResource extends Resource
{
    protected static ?string $model = Refund::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Paiements';

    protected static ?string $navigationLabel = 'Remboursements';

    protected static ?string $modelLabel = 'remboursement';

    protected static ?string $pluralModelLabel = 'remboursements';

    protected static ?string $viewPermission = 'refunds.view';

    protected static ?string $managePermission = 'refunds.approve';

    protected static ?int $navigationSort = 20;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = false;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return RefundForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RefundInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RefundsTable::configure($table);
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
            'index' => ListRefunds::route('/'),
            'create' => CreateRefund::route('/create'),
            'view' => ViewRefund::route('/{record}'),
            'edit' => EditRefund::route('/{record}/edit'),
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
