<?php

namespace App\Filament\Resources\Platform\LegalPages;

use App\Filament\Resources\Platform\LegalPages\Pages\CreateLegalPage;
use App\Filament\Resources\Platform\LegalPages\Pages\EditLegalPage;
use App\Filament\Resources\Platform\LegalPages\Pages\ListLegalPages;
use App\Filament\Resources\Platform\LegalPages\Pages\ViewLegalPage;
use App\Filament\Resources\Platform\LegalPages\Schemas\LegalPageForm;
use App\Filament\Resources\Platform\LegalPages\Schemas\LegalPageInfolist;
use App\Filament\Resources\Platform\LegalPages\Tables\LegalPagesTable;
use App\Models\Platform\LegalPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LegalPageResource extends Resource
{
    protected static ?string $model = LegalPage::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Plateforme';

    protected static ?string $navigationLabel = 'Pages legales';

    protected static ?string $modelLabel = 'page legale';

    protected static ?string $pluralModelLabel = 'pages legales';

    protected static ?string $viewPermission = 'settings.manage';

    protected static ?string $managePermission = 'legal_pages.manage';

    protected static ?int $navigationSort = 30;

    protected static bool $allowCreate = true;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return LegalPageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LegalPageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LegalPagesTable::configure($table);
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
            'index' => ListLegalPages::route('/'),
            'create' => CreateLegalPage::route('/create'),
            'view' => ViewLegalPage::route('/{record}'),
            'edit' => EditLegalPage::route('/{record}/edit'),
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
