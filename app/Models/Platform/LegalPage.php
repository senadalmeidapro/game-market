<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Platform;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LegalPage
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $body
 * @property string $locale
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class LegalPage extends Model
{
    protected $table = 'legal_pages';

    protected $fillable = [
        'slug',
        'title',
        'body',
        'locale',
    ];
}
