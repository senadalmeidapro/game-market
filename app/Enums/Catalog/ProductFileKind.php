<?php

namespace App\Enums\Catalog;

enum ProductFileKind: string
{
    case Build = 'build';
    case Original = 'original';
    case Preview = 'preview';
}
