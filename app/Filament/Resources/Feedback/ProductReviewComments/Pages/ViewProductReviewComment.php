<?php

namespace App\Filament\Resources\Feedback\ProductReviewComments\Pages;

use App\Filament\Resources\Feedback\ProductReviewComments\ProductReviewCommentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProductReviewComment extends ViewRecord
{
    protected static string $resource = ProductReviewCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
