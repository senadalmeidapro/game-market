<?php

namespace App\Filament\Resources\Catalog\Genres;

use App\Filament\Resources\Catalog\Genres\Pages\CreateGenre;
use App\Filament\Resources\Catalog\Genres\Pages\EditGenre;
use App\Filament\Resources\Catalog\Genres\Pages\ListGenres;
use App\Filament\Resources\Catalog\Genres\Pages\ViewGenre;
use App\Filament\Resources\Catalog\Genres\Schemas\GenreForm;
use App\Filament\Resources\Catalog\Genres\Schemas\GenreInfolist;
use App\Filament\Resources\Catalog\Genres\Tables\GenresTable;
use App\Models\Catalog\Genre;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class GenreResource extends Resource
{
    protected static ?string $model = Genre::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalogue';

    protected static ?string $navigationLabel = 'Genres';

    protected static ?string $modelLabel = 'genre';

    protected static ?string $pluralModelLabel = 'genres';

    protected static ?string $viewPermission = 'catalog.view';

    protected static ?string $managePermission = 'catalog.manage';

    protected static ?int $navigationSort = 60;

    protected static bool $allowCreate = true;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return GenreForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GenreInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GenresTable::configure($table);
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
            'index' => ListGenres::route('/'),
            'create' => CreateGenre::route('/create'),
            'view' => ViewGenre::route('/{record}'),
            'edit' => EditGenre::route('/{record}/edit'),
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
