<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Catalog;

use App\Enums\Catalog\PlatformCode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Platform
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|ProductFile[] $product_files
 * @property Collection|SystemRequirement[] $system_requirements
 */
class Platform extends Model
{
    protected $table = 'platforms';

    protected $fillable = [
        'code',
        'name',
    ];

    protected $casts = [
        'code' => PlatformCode::class,
    ];

    public function productFiles(): HasMany
    {
        return $this->hasMany(ProductFile::class);
    }

    public function systemRequirements(): HasMany
    {
        return $this->hasMany(SystemRequirement::class);
    }
}
