<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Commerce;

use App\Models\Catalog\Product;
use App\Models\Library\Licence;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class OrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $title_snapshot
 * @property int $unit_price_amount
 * @property int $discount_amount
 * @property int $tax_amount
 * @property int $total_amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Order $order
 * @property Product $product
 * @property Collection|Licence[] $licences
 */
class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $casts = [
        'order_id' => 'int',
        'product_id' => 'int',
        'unit_price_amount' => 'int',
        'discount_amount' => 'int',
        'tax_amount' => 'int',
        'total_amount' => 'int',
    ];

    protected $fillable = [
        'order_id',
        'product_id',
        'title_snapshot',
        'unit_price_amount',
        'discount_amount',
        'tax_amount',
        'total_amount',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function licence(): HasOne
    {
        return $this->hasOne(Licence::class);
    }
}
