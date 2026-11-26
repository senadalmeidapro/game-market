<?php

namespace App\Filament\Resources\Feedback\ProductReviews;

use App\Filament\Resources\Feedback\ProductReviews\Pages\CreateProductReview;
use App\Filament\Resources\Feedback\ProductReviews\Pages\EditProductReview;
use App\Filament\Resources\Feedback\ProductReviews\Pages\ListProductReviews;
use App\Filament\Resources\Feedback\ProductReviews\Pages\ViewProductReview;
use App\Filament\Resources\Feedback\ProductReviews\Schemas\ProductReviewForm;
use App\Filament\Resources\Feedback\ProductReviews\Schemas\ProductReviewInfolist;
use App\Filament\Resources\Feedback\ProductReviews\Tables\ProductReviewsTable;
use App\Models\Feedback\ProductReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductReviewResource extends Resource
{
    protected static ?string $model = ProductReview::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Avis et commentaires';

    protected static ?string $navigationLabel = 'Avis produits';

    protected static ?string $modelLabel = 'avis';

    protected static ?string $pluralModelLabel = 'avis produits';

    protected static ?string $viewPermission = 'reviews.view';

    protected static ?string $managePermission = 'reviews.moderate';

    protected static ?int $navigationSort = 10;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return ProductReviewForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductReviewInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductReviewsTable::configure($table);
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
            'index' => ListProductReviews::route('/'),
            'create' => CreateProductReview::route('/create'),
            'view' => ViewProductReview::route('/{record}'),
            'edit' => EditProductReview::route('/{record}/edit'),
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
