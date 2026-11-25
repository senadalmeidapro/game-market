<?php

namespace App\Enums\Catalog;

enum PlatformCode: string
{
    case Windows = 'windows';
    case Linux = 'linux';
    case Other = 'other';
}
