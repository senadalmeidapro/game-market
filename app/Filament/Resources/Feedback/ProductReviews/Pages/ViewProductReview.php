<?php

namespace App\Filament\Resources\Feedback\ProductReviews\Pages;

use App\Filament\Resources\Feedback\ProductReviews\ProductReviewResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProductReview extends ViewRecord
{
    protected static string $resource = ProductReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
