<?php

namespace App\Enums\Catalog;

enum PublisherContractStatus: string
{
    case Unsigned = 'unsigned';
    case Signed = 'signed';
}
