<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Platform;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class AuditLog
 *
 * @property int $id
 * @property int|null $actor_id
 * @property string $action
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $old_values
 * @property string|null $new_values
 * @property string|null $ip_address
 * @property Carbon $created_at
 * @property User|null $user
 */
class AuditLog extends Model
{
    protected $table = 'audit_logs';

    public $timestamps = false;

    protected $casts = [
        'actor_id' => 'int',
        'subject_id' => 'int',
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    protected $fillable = [
        'actor_id',
        'action',
        'subject_type',
        'subject_id',
        'old_values',
        'new_values',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
