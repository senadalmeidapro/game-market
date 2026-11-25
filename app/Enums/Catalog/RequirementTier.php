<?php

namespace App\Enums\Catalog;

enum RequirementTier: string
{
    case Minimum = 'minimum';
    case Recommended = 'recommended';
}
