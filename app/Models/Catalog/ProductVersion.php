<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Catalog;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ProductVersion
 *
 * @property int $id
 * @property int $product_id
 * @property string $version_number
 * @property string|null $release_notes
 * @property Carbon|null $released_at
 * @property string $status
 * @property bool $is_latest
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Product $product
 * @property Collection|ProductFile[] $product_files
 */
class ProductVersion extends Model
{
    protected $table = 'product_versions';

    protected $casts = [
        'product_id' => 'int',
        'released_at' => 'datetime',
        'is_latest' => 'bool',
    ];

    protected $fillable = [
        'product_id',
        'version_number',
        'release_notes',
        'released_at',
        'status',
        'is_latest',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProductFile::class);
    }
}
