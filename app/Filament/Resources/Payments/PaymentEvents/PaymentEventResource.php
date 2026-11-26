<?php

namespace App\Filament\Resources\Payments\PaymentEvents;

use App\Filament\Resources\Payments\PaymentEvents\Pages\CreatePaymentEvent;
use App\Filament\Resources\Payments\PaymentEvents\Pages\EditPaymentEvent;
use App\Filament\Resources\Payments\PaymentEvents\Pages\ListPaymentEvents;
use App\Filament\Resources\Payments\PaymentEvents\Pages\ViewPaymentEvent;
use App\Filament\Resources\Payments\PaymentEvents\Schemas\PaymentEventForm;
use App\Filament\Resources\Payments\PaymentEvents\Schemas\PaymentEventInfolist;
use App\Filament\Resources\Payments\PaymentEvents\Tables\PaymentEventsTable;
use App\Models\Payments\PaymentEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PaymentEventResource extends Resource
{
    protected static ?string $model = PaymentEvent::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Paiements';

    protected static ?string $navigationLabel = 'Evenements de paiement';

    protected static ?string $modelLabel = 'evenement de paiement';

    protected static ?string $pluralModelLabel = 'evenements de paiement';

    protected static ?string $viewPermission = 'payments.view';

    protected static ?string $managePermission = null;

    protected static ?int $navigationSort = 30;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = false;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static ?string $recordTitleAttribute = 'provider_event_id';

    public static function form(Schema $schema): Schema
    {
        return PaymentEventForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentEventInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentEventsTable::configure($table);
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
            'index' => ListPaymentEvents::route('/'),
            'create' => CreatePaymentEvent::route('/create'),
            'view' => ViewPaymentEvent::route('/{record}'),
            'edit' => EditPaymentEvent::route('/{record}/edit'),
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
