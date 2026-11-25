<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class GenreProduct
 *
 * @property int $genre_id
 * @property int $product_id
 * @property Genre $genre
 * @property Product $product
 */
class GenreProduct extends Pivot
{
    protected $table = 'genre_product';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'genre_id' => 'int',
        'product_id' => 'int',
    ];

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
