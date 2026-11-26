<?php

namespace App\Filament\Resources\Feedback\ProductReviews\Pages;

use App\Filament\Resources\Feedback\ProductReviews\ProductReviewResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductReview extends CreateRecord
{
    protected static string $resource = ProductReviewResource::class;
}
