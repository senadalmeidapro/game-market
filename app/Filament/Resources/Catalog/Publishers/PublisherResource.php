<?php

namespace App\Filament\Resources\Catalog\Publishers;

use App\Filament\Resources\Catalog\Publishers\Pages\CreatePublisher;
use App\Filament\Resources\Catalog\Publishers\Pages\EditPublisher;
use App\Filament\Resources\Catalog\Publishers\Pages\ListPublishers;
use App\Filament\Resources\Catalog\Publishers\Pages\ViewPublisher;
use App\Filament\Resources\Catalog\Publishers\Schemas\PublisherForm;
use App\Filament\Resources\Catalog\Publishers\Schemas\PublisherInfolist;
use App\Filament\Resources\Catalog\Publishers\Tables\PublishersTable;
use App\Models\Catalog\Publisher;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PublisherResource extends Resource
{
    protected static ?string $model = Publisher::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    protected static ?string $navigationLabel = 'Editeurs';

    protected static ?string $modelLabel = 'editeur';

    protected static ?string $pluralModelLabel = 'editeurs';

    protected static ?string $viewPermission = 'catalog.view';

    protected static ?string $managePermission = 'catalog.manage';

    protected static ?int $navigationSort = 80;

    protected static bool $allowCreate = true;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PublisherForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PublisherInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PublishersTable::configure($table);
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
            'index' => ListPublishers::route('/'),
            'create' => CreatePublisher::route('/create'),
            'view' => ViewPublisher::route('/{record}'),
            'edit' => EditPublisher::route('/{record}/edit'),
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
        return static::$allowDelete
            && filled(static::$managePermission)
            && auth()->user()?->can(static::$managePermission) === true
            && ! $record->products()->exists();
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
