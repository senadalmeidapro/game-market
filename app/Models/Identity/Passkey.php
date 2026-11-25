<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Identity;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Passkey
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $credential_id
 * @property string $credential
 * @property Carbon|null $last_used_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property User $user
 */
class Passkey extends Model
{
    protected $table = 'passkeys';

    protected $casts = [
        'user_id' => 'int',
        'last_used_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'credential_id',
        'credential',
        'last_used_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
