<?php

namespace App\Enums\Catalog;

enum ProductFileStatus: string
{
    case Uploading = 'uploading';
    case Uploaded = 'uploaded';
    case Verified = 'verified';
    case Failed = 'failed';
}
