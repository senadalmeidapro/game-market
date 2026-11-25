<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CategoryProduct
 *
 * @property int $category_id
 * @property int $product_id
 * @property Category $category
 * @property Product $product
 */
class CategoryProduct extends Pivot
{
    protected $table = 'category_product';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'category_id' => 'int',
        'product_id' => 'int',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
