<?php

namespace App\Filament\Resources\Feedback\ProductReviewComments;

use App\Filament\Resources\Feedback\ProductReviewComments\Pages\CreateProductReviewComment;
use App\Filament\Resources\Feedback\ProductReviewComments\Pages\EditProductReviewComment;
use App\Filament\Resources\Feedback\ProductReviewComments\Pages\ListProductReviewComments;
use App\Filament\Resources\Feedback\ProductReviewComments\Pages\ViewProductReviewComment;
use App\Filament\Resources\Feedback\ProductReviewComments\Schemas\ProductReviewCommentForm;
use App\Filament\Resources\Feedback\ProductReviewComments\Schemas\ProductReviewCommentInfolist;
use App\Filament\Resources\Feedback\ProductReviewComments\Tables\ProductReviewCommentsTable;
use App\Models\Feedback\ProductReviewComment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductReviewCommentResource extends Resource
{
    protected static ?string $model = ProductReviewComment::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Avis et commentaires';

    protected static ?string $navigationLabel = 'Commentaires';

    protected static ?string $modelLabel = 'commentaire';

    protected static ?string $pluralModelLabel = 'commentaires';

    protected static ?string $viewPermission = 'reviews.view';

    protected static ?string $managePermission = 'reviews.moderate';

    protected static ?int $navigationSort = 20;

    protected static bool $allowCreate = false;

    protected static bool $allowEdit = true;

    protected static bool $allowDelete = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return ProductReviewCommentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductReviewCommentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductReviewCommentsTable::configure($table);
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
            'index' => ListProductReviewComments::route('/'),
            'create' => CreateProductReviewComment::route('/create'),
            'view' => ViewProductReviewComment::route('/{record}'),
            'edit' => EditProductReviewComment::route('/{record}/edit'),
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
