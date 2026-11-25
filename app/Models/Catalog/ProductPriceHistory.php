<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Catalog;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ProductPriceHistory
 *
 * @property int $id
 * @property int $product_id
 * @property int $old_price_amount
 * @property int $new_price_amount
 * @property int|null $changed_by
 * @property Carbon $changed_at
 * @property Product $product
 * @property User|null $user
 */
class ProductPriceHistory extends Model
{
    protected $table = 'product_price_histories';

    public $timestamps = false;

    protected $casts = [
        'product_id' => 'int',
        'old_price_amount' => 'int',
        'new_price_amount' => 'int',
        'changed_by' => 'int',
        'changed_at' => 'datetime',
    ];

    protected $fillable = [
        'product_id',
        'old_price_amount',
        'new_price_amount',
        'changed_by',
        'changed_at',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
