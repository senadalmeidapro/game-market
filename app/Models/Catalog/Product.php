<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Catalog;

use App\Models\Commerce\CartItem;
use App\Models\Commerce\OrderItem;
use App\Models\Commerce\WishlistItem;
use App\Models\Library\Licence;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Product
 *
 * @property int $id
 * @property int|null $publisher_id
 * @property string $type
 * @property string $title
 * @property string $slug
 * @property string|null $summary
 * @property string|null $description
 * @property int $price_amount
 * @property string $status
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Publisher|null $publisher
 * @property Collection|Category[] $categories
 * @property Collection|Genre[] $genres
 * @property Collection|ProductVersion[] $product_versions
 * @property Collection|SystemRequirement[] $system_requirements
 * @property Collection|ProductPriceHistory[] $product_price_histories
 * @property Collection|CartItem[] $cart_items
 * @property Collection|WishlistItem[] $wishlist_items
 * @property Collection|OrderItem[] $order_items
 * @property Collection|Licence[] $licences
 */
class Product extends Model
{
    protected $table = 'products';

    protected $casts = [
        'publisher_id' => 'int',
        'price_amount' => 'int',
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'publisher_id',
        'type',
        'title',
        'slug',
        'summary',
        'description',
        'price_amount',
        'status',
        'published_at',
    ];

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->using(CategoryProduct::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class)->using(GenreProduct::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ProductVersion::class);
    }

    public function systemRequirements(): HasMany
    {
        return $this->hasMany(SystemRequirement::class);
    }

    public function priceHistories(): HasMany
    {
        return $this->hasMany(ProductPriceHistory::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function licences(): HasMany
    {
        return $this->hasMany(Licence::class);
    }
}
