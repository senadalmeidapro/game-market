<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Commerce;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PromotionUsage
 *
 * @property int $id
 * @property int $promotion_id
 * @property int $order_id
 * @property int $user_id
 * @property int $discount_amount
 * @property Carbon $used_at
 * @property Promotion $promotion
 * @property Order $order
 * @property User $user
 */
class PromotionUsage extends Model
{
    protected $table = 'promotion_usages';

    public $timestamps = false;

    protected $casts = [
        'promotion_id' => 'int',
        'order_id' => 'int',
        'user_id' => 'int',
        'discount_amount' => 'int',
        'used_at' => 'datetime',
    ];

    protected $fillable = [
        'promotion_id',
        'order_id',
        'user_id',
        'discount_amount',
        'used_at',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
