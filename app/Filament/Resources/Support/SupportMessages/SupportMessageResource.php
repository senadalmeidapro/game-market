<?php

namespace App\Filament\Resources\Support\SupportMessages;

use App\Filament\Resources\Support\SupportMessages\Pages\CreateSupportMessage;
use App\Filament\Resources\Support\SupportMessages\Pages\EditSupportMessage;
use App\Filament\Resources\Support\SupportMessages\Pages\ListSupportMessages;
use App\Filament\Resources\Support\SupportMessages\Pages\ViewSupportMessage;
use App\Filament\Resources\Support\SupportMessages\Schemas\SupportMessageForm;
use App\Filament\Resources\Support\SupportMessages\Schemas\SupportMessageInfolist;
use App\Filament\Resources\Support\SupportMessages\Tables\SupportMessagesTable;
use App\Models\Support\SupportMessage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SupportMessageResource extends Resource
{
    protected static ?string $model = SupportMessage::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Support';

    protected static ?string $navigationLabel = 'Messages';

    protected static ?string $modelLabel = 'message';

    protected static ?string $pluralModelLabel = 'messages';

    protected static ?string $viewPermission = 'tickets.view';

    protected static ?string $managePermission = null;

    protected static ?int $navigationSort = 20;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = false;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return SupportMessageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupportMessageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupportMessagesTable::configure($table);
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
            'index' => ListSupportMessages::route('/'),
            'create' => CreateSupportMessage::route('/create'),
            'view' => ViewSupportMessage::route('/{record}'),
            'edit' => EditSupportMessage::route('/{record}/edit'),
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
