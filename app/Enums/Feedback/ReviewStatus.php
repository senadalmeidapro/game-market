<?php

namespace App\Enums\Feedback;

enum ReviewStatus: string
{
    case Pending = 'pending';
    case Published = 'published';
    case Hidden = 'hidden';
}
