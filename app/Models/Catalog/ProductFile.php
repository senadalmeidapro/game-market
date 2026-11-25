<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Catalog;

use App\Models\Library\DownloadGrant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ProductFile
 *
 * @property int $id
 * @property int $product_version_id
 * @property int|null $platform_id
 * @property string $kind
 * @property string $filename
 * @property string $storage_key
 * @property int|null $size_bytes
 * @property string|null $sha256
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property ProductVersion $product_version
 * @property Platform|null $platform
 * @property Collection|DownloadGrant[] $download_grants
 */
class ProductFile extends Model
{
    protected $table = 'product_files';

    protected $casts = [
        'product_version_id' => 'int',
        'platform_id' => 'int',
        'size_bytes' => 'int',
    ];

    protected $fillable = [
        'product_version_id',
        'platform_id',
        'kind',
        'filename',
        'storage_key',
        'size_bytes',
        'sha256',
        'status',
    ];

    public function version(): BelongsTo
    {
        return $this->belongsTo(ProductVersion::class);
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function downloadGrants(): HasMany
    {
        return $this->hasMany(DownloadGrant::class);
    }
}
