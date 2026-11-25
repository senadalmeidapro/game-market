<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Catalog;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Publisher
 *
 * @property int $id
 * @property string $name
 * @property string|null $contact_email
 * @property string|null $country
 * @property string $contract_status
 * @property string|null $contract_storage_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|Product[] $products
 */
class Publisher extends Model
{
    protected $table = 'publishers';

    protected $fillable = [
        'name',
        'contact_email',
        'country',
        'contract_status',
        'contract_storage_key',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
