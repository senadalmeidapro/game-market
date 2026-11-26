<?php

namespace App\Filament\Resources\Feedback\ProductReviewComments\Pages;

use App\Filament\Resources\Feedback\ProductReviewComments\ProductReviewCommentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductReviewComments extends ListRecords
{
    protected static string $resource = ProductReviewCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
