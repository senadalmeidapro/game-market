<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Commerce;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Promotion
 *
 * @property int $id
 * @property string $code
 * @property string $type
 * @property int $value
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property int|null $max_uses
 * @property int $max_uses_per_user
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|Order[] $orders
 * @property Collection|PromotionUsage[] $promotion_usages
 */
class Promotion extends Model
{
    protected $table = 'promotions';

    protected $casts = [
        'value' => 'int',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'max_uses' => 'int',
        'max_uses_per_user' => 'int',
        'is_active' => 'bool',
    ];

    protected $fillable = [
        'code',
        'type',
        'value',
        'starts_at',
        'ends_at',
        'max_uses',
        'max_uses_per_user',
        'is_active',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
