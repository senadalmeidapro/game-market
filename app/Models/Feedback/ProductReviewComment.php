<?php

namespace App\Models\Feedback;

use App\Enums\Feedback\ReviewCommentStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductReviewComment extends Model
{
    protected $fillable = [
        'product_review_id',
        'user_id',
        'parent_comment_id',
        'body',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ReviewCommentStatus::class,
        ];
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(ProductReview::class, 'product_review_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_comment_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_comment_id');
    }
}
