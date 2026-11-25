<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Library;

use App\Models\Catalog\ProductFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DownloadGrant
 *
 * @property int $id
 * @property int $licence_id
 * @property int $product_file_id
 * @property int $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon $issued_at
 * @property Carbon $expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Licence $licence
 * @property ProductFile $product_file
 * @property User $user
 */
class DownloadGrant extends Model
{
    protected $table = 'download_grants';

    protected $casts = [
        'licence_id' => 'int',
        'product_file_id' => 'int',
        'user_id' => 'int',
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $fillable = [
        'licence_id',
        'product_file_id',
        'user_id',
        'ip_address',
        'user_agent',
        'issued_at',
        'expires_at',
    ];

    public function licence(): BelongsTo
    {
        return $this->belongsTo(Licence::class);
    }

    public function productFile(): BelongsTo
    {
        return $this->belongsTo(ProductFile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
