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
 * Class WishlistItem
 *
 * @property int $id
 * @property int $wishlist_id
 * @property int $product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Wishlist $wishlist
 * @property Product $product
 */
class WishlistItem extends Model
{
    protected $table = 'wishlist_items';

    protected $casts = [
        'wishlist_id' => 'int',
        'product_id' => 'int',
    ];

    protected $fillable = [
        'wishlist_id',
        'product_id',
    ];

    public function wishlist(): BelongsTo
    {
        return $this->belongsTo(Wishlist::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
