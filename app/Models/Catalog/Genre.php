<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Catalog;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Genre
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property Genre|null $parent
 * @property Collection|Genre[] $children
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|Product[] $products
 */
class Genre extends Model
{
    protected $table = 'genres';

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
    ];

    protected $casts = [
        'parent_id' => 'int',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->using(GenreProduct::class);
    }
}
