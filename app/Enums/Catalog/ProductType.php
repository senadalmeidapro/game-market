<?php

namespace App\Enums\Catalog;

enum ProductType: string
{
    case Game = 'game';
    case Photo = 'photo';
    case Video = 'video';
}
