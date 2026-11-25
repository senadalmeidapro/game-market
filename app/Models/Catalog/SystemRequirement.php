<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Catalog;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SystemRequirement
 *
 * @property int $id
 * @property int $product_id
 * @property int $platform_id
 * @property string $tier
 * @property string|null $os
 * @property string|null $cpu
 * @property string|null $ram
 * @property string|null $gpu
 * @property string|null $storage
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Product $product
 * @property Platform $platform
 */
class SystemRequirement extends Model
{
    protected $table = 'system_requirements';

    protected $casts = [
        'product_id' => 'int',
        'platform_id' => 'int',
    ];

    protected $fillable = [
        'product_id',
        'platform_id',
        'tier',
        'os',
        'cpu',
        'ram',
        'gpu',
        'storage',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}
