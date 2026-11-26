<?php

namespace App\Filament\Resources\Commerce\Promotions;

use App\Filament\Resources\Commerce\Promotions\Pages\CreatePromotion;
use App\Filament\Resources\Commerce\Promotions\Pages\EditPromotion;
use App\Filament\Resources\Commerce\Promotions\Pages\ListPromotions;
use App\Filament\Resources\Commerce\Promotions\Pages\ViewPromotion;
use App\Filament\Resources\Commerce\Promotions\Schemas\PromotionForm;
use App\Filament\Resources\Commerce\Promotions\Schemas\PromotionInfolist;
use App\Filament\Resources\Commerce\Promotions\Tables\PromotionsTable;
use App\Models\Commerce\Promotion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Commerce';

    protected static ?string $navigationLabel = 'Promotions';

    protected static ?string $modelLabel = 'promotion';

    protected static ?string $pluralModelLabel = 'promotions';

    protected static ?string $viewPermission = 'promotions.view';

    protected static ?string $managePermission = 'promotions.manage';

    protected static ?int $navigationSort = 20;

    protected static bool $allowCreate = true;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return PromotionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PromotionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PromotionsTable::configure($table);
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
            'index' => ListPromotions::route('/'),
            'create' => CreatePromotion::route('/create'),
            'view' => ViewPromotion::route('/{record}'),
            'edit' => EditPromotion::route('/{record}/edit'),
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
