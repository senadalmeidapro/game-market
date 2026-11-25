<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Commerce;

use App\Models\Catalog\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CartItem
 *
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Cart $cart
 * @property Product $product
 */
class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $casts = [
        'cart_id' => 'int',
        'product_id' => 'int',
    ];

    protected $fillable = [
        'cart_id',
        'product_id',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
