<?php

namespace App\Enums\Catalog;

enum ProductStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Unpublished = 'unpublished';
    case Archived = 'archived';
}
