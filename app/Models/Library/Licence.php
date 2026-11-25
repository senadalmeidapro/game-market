<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Library;

use App\Models\Catalog\Product;
use App\Models\Commerce\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Licence
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $order_item_id
 * @property string $status
 * @property Carbon $issued_at
 * @property Carbon|null $revoked_at
 * @property string|null $revoked_reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property User $user
 * @property Product $product
 * @property OrderItem $order_item
 * @property Collection|DownloadGrant[] $download_grants
 */
class Licence extends Model
{
    protected $table = 'licences';

    protected $casts = [
        'user_id' => 'int',
        'product_id' => 'int',
        'order_item_id' => 'int',
        'issued_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'product_id',
        'order_item_id',
        'status',
        'issued_at',
        'revoked_at',
        'revoked_reason',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function downloadGrants(): HasMany
    {
        return $this->hasMany(DownloadGrant::class);
    }
}
