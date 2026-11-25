<?php

namespace App\Enums\Feedback;

enum ReviewCommentStatus: string
{
    case Published = 'published';
    case Hidden = 'hidden';
}
