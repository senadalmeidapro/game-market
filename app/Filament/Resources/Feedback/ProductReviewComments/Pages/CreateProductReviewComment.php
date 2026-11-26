<?php

namespace App\Filament\Resources\Feedback\ProductReviewComments\Pages;

use App\Filament\Resources\Feedback\ProductReviewComments\ProductReviewCommentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductReviewComment extends CreateRecord
{
    protected static string $resource = ProductReviewCommentResource::class;
}
