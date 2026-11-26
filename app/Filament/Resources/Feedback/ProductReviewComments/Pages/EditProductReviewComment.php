<?php

namespace App\Filament\Resources\Feedback\ProductReviewComments\Pages;

use App\Filament\Resources\Feedback\ProductReviewComments\ProductReviewCommentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProductReviewComment extends EditRecord
{
    protected static string $resource = ProductReviewCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
