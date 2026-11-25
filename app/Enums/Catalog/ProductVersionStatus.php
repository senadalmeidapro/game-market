<?php

namespace App\Enums\Catalog;

enum ProductVersionStatus: string
{
    case Draft = 'draft';
    case Processing = 'processing';
    case Distributable = 'distributable';
    case Withdrawn = 'withdrawn';
}
